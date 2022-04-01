<?php

namespace Tests\App\Controller\Api\AdherentMessage;

use App\AdherentMessage\Command\AdherentMessageChangeCommand;
use App\Entity\Adherent;
use App\Scope\Scope;
use App\Scope\ScopeEnum;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Tests\App\Test\HttpClient\MockHttpClient;
use Tests\App\Test\HttpClient\ResponseFactory;

/**
 * @group functional
 * @group api
 */
class MessageControllerTest extends AbstractMessageControllerTest
{
    private const URL = '/api/v3/adherent_messages';

    private ?MockHttpClient $httpClientMock = null;
    private ?ResponseFactory $responseFactory = null;
    private ?MessageBusInterface $bus = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client->disableReboot();

        $this->httpClientMock = $this->get(MockHttpClient::class);
        $this->responseFactory = $this->get(ResponseFactory::class);
        $this->bus = $this->get(MessageBusInterface::class);
    }

    public function testICanCreateAndUpdateMessageWithContent(): void
    {
        $this->responseFactory
            ->setDefaultResponse('{}')
            ->addAllowedResponse('POST /3.0/campaigns', json_encode(['id' => 42]))
        ;

        foreach ($this->getMessageRedactors() as $row) {
            /** @var Adherent $adherent */
            $adherent = $row['adherent'];
            /** @var Scope[] $scopes */
            $scopes = $row['scopes'];

            foreach ($scopes as $scope) {
                $fromAdherent = $this->getFromAdherent($scope, $adherent);

                $response = $this->sendRequest('POST', self::URL.'?scope='.$scope->getCode(), $adherent->getEmailAddress(), [
                    'type' => $this->getScopeCode($scope),
                    'label' => 'Label du message qui permet de le retrouver ds la liste des messages envoyés',
                    'subject' => "L'objet du mail",
                    'content' => '<table>...</table>',
                    'json_content' => '{"foo": "bar", "items": [1, 2, true, "hello world"]}',
                ]);

                $this->assertResponseIsSuccessful();

                $messages = $this->findMessages(AdherentMessageChangeCommand::class);
                self::assertCount(1, $messages);
                $this->bus->dispatch($messages[0]->with(new ReceivedStamp('abc')));

                $messages = $this->findMessages(AdherentMessageChangeCommand::class, true);
                self::assertCount(1, $messages);

                self::assertCount(2, $this->httpClientMock->requests);
                $mainRequest = array_shift($this->httpClientMock->requests);

                self::assertSame('POST', $mainRequest['method']);
                self::assertSame('/3.0/campaigns', $mainRequest['url']);

                $this->assertMatchArray([
                    'type' => 'regular',
                    'settings' => [
                        'template_id' => '@integer@',
                        'subject_line' => "[@string@] L'objet du mail",
                        'title' => $fromAdherent->getFullName().' - @string@',
                        'reply_to' => $this->prepareReplyto($scope, $fromAdherent),
                        'from_name' => $this->prepareFromName($scope, $fromAdherent),
                    ],
                ], $mainRequest['options']['json']);

                $contentRequest = array_shift($this->httpClientMock->requests);

                self::assertSame('PUT', $contentRequest['method']);
                self::assertSame('/3.0/campaigns/42/content', $contentRequest['url']);

                $this->assertMatchArray([
                    'template' => [
                        'id' => 13,
                        'sections' => array_merge([
                            'content' => '<table>...</table>',
                        ], $this->prepareContentSections($scope, $fromAdherent)),
                    ],
                ], $contentRequest['options']['json']);

                $data = json_decode($response->getContent(), true);

                $this->sendRequest('PUT', self::URL.'/'.$data['uuid'].'?scope='.$scope->getCode(), $adherent->getEmailAddress(), [
                    'label' => 'Label !!1',
                    'subject' => "L'objet du mail 2",
                    'content' => '<table>...</table>',
                    'json_content' => '{"foo": "bar", "items": [1, 2, true, "hello world"]}',
                ]);
                $this->assertResponseIsSuccessful();
                $this->assertMessageIsDispatched(AdherentMessageChangeCommand::class);

                $messages = $this->findMessages(AdherentMessageChangeCommand::class, true);
                self::assertCount(1, $messages);
            }
        }
    }

    private function prepareFromName(Scope $scope, Adherent $adherent): string
    {
        if (ScopeEnum::CORRESPONDENT === $this->getScopeCode($scope)) {
            return $adherent->getFirstName().' | Campagne 2022';
        }

        return $adherent->getFullName().' | La République En Marche !';
    }

    private function prepareReplyTo(Scope $scope, Adherent $adherent): string
    {
        if (ScopeEnum::CORRESPONDENT === $this->getScopeCode($scope)) {
            return 'ne-pas-repondre@je-mengage.fr';
        }

        return 'ne-pas-repondre@en-marche.fr';
    }

    private function prepareContentSections(Scope $scope, Adherent $adherent): array
    {
        $contentSections = [];

        if (\in_array($this->getScopeCode($scope), [
            ScopeEnum::REFERENT,
            ScopeEnum::CANDIDATE,
            ScopeEnum::DEPUTY,
            ScopeEnum::SENATOR,
        ])) {
            $contentSections = [
                'full_name' => $adherent->getFullName(),
                'first_name' => $adherent->getFirstName(),
                'reply_to_button' => sprintf('<a class="mcnButton" title="Répondre" href="mailto:%s" target="_blank">Répondre</a>', $adherent->getEmailAddress()),
                'reply_to_link' => sprintf('<a title="Répondre" href="mailto:%s" target="_blank">Répondre</a>', $adherent->getEmailAddress()),
            ];
        }

        if (ScopeEnum::DEPUTY === $this->getScopeCode($scope)) {
            $contentSections['district_name'] = '@string@';
        }

        return $contentSections;
    }

    private function getFromAdherent(Scope $scope, Adherent $adherent): Adherent
    {
        if ($scope->getDelegatorCode()) {
            return $scope->getDelegator();
        }

        return $adherent;
    }

    private function getScopeCode(Scope $scope): string
    {
        return $scope->getDelegatorCode() ?? $scope->getCode();
    }
}
