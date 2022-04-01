<?php

namespace App\Messenger\MessageRecorder;

use Symfony\Component\Messenger\Envelope;

interface MessageRecorderInterface
{
    public function record(Envelope $message): void;

    /**
     * @return Envelope[]|array
     */
    public function getMessages(): array;

    public function removeMessages(int $key): void;
}
