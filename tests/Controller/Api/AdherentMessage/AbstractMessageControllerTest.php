<?php

namespace Tests\App\Controller\Api\AdherentMessage;

use App\DataFixtures\ORM\LoadAdherentData;
use App\DataFixtures\ORM\LoadClientData;
use App\OAuth\Model\GrantTypeEnum;
use App\Scope\FeatureEnum;
use App\Scope\GeneralScopeGenerator;
use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use Symfony\Component\HttpFoundation\Response;
use Tests\App\AbstractWebCaseTest;
use Tests\App\Controller\ApiControllerTestTrait;
use Tests\App\Controller\ControllerTestTrait;
use Tests\App\MessengerTestTrait;

abstract class AbstractMessageControllerTest extends AbstractWebCaseTest
{
    use ControllerTestTrait;
    use ApiControllerTestTrait;
    use MessengerTestTrait;
    use PHPMatcherAssertions;

    private array $tokens = [];

    protected function getMessageRedactors(): array
    {
        /** @var GeneralScopeGenerator $scopeGenerator */
        $scopeGenerator = $this->get(GeneralScopeGenerator::class);

        $adherents = [];
        foreach ($this->getAdherentRepository()->findAll() as $adherent) {
            $scopes = $scopeGenerator->generateScopes($adherent);
            $tmp = [];
            foreach ($scopes as $scope) {
                if ($scope->hasFeature(FeatureEnum::MESSAGES)) {
                    $tmp[] = $scope;
                }
            }

            if ($tmp) {
                $adherents[] = ['adherent' => $adherent, 'scopes' => $tmp];
            }
        }

        return $adherents;
    }

    protected function sendRequest(string $method, string $url, string $email, array $data = null): Response
    {
        $this->client->request(
            $method,
            $url,
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.($this->tokens[$email] ?? ($this->tokens[$email] = $this->getToken($email))),
                'CONTENT_TYPE' => 'application/json',
            ],
            $data ? json_encode($data) : null
        );

        return $this->client->getResponse();
    }

    private function getToken(string $email): string
    {
        return $this->getAccessToken(
            LoadClientData::CLIENT_12_UUID,
            'BHLfR-MWLVBF@Z.ZBh4EdTFJ',
            GrantTypeEnum::PASSWORD,
            null,
            $email,
            LoadAdherentData::DEFAULT_PASSWORD
        );
    }

    protected function assertMatchArray(array $expected, array $actual): void
    {
        $this->assertMatchesPattern($expected, $actual);
    }
}
