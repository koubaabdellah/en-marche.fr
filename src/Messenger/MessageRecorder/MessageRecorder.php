<?php

namespace App\Messenger\MessageRecorder;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

class MessageRecorder implements MessageRecorderInterface
{
    private array $messages = [];

    public function record(Envelope $message): void
    {
        if (!$message->all(ReceivedStamp::class)) {
            $this->messages[] = $message;
        }
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function removeMessages(int $key): void
    {
        unset($this->messages[$key]);
    }
}
