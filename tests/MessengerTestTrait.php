<?php

namespace Tests\App;

use App\Messenger\MessageRecorder\MessageRecorderInterface;
use Symfony\Component\Messenger\Envelope;

trait MessengerTestTrait
{
    abstract protected function getMessageRecorder(): MessageRecorderInterface;

    public function assertMessageIsDispatched(string $class, ?int $count = null, ?string $help = null): void
    {
        $messages = $this->findMessages($class);

        if (null === $count) {
            self::assertNotEmpty($messages);
        } else {
            self::assertCount($count, $messages, $help ?? "Message $class not found $count times.");
        }
    }

    public function assertMessageIsNotDispatched(string $unexpectedMessageClass): void
    {
        $this->assertMessageIsDispatched($unexpectedMessageClass, 0, "Message $unexpectedMessageClass found, but not expected.");
    }

    /** @return Envelope[] */
    public function findMessages(string $class, bool $clear = false): array
    {
        $recorder = $this->getMessageRecorder();
        $messages = [];
        foreach ($recorder->getMessages() as $key => $envelope) {
            if (is_a($envelope->getMessage(), $class)) {
                $messages[] = $envelope;
                if ($clear) {
                    $recorder->removeMessages($key);
                }
            }
        }

        return $messages;
    }
}
