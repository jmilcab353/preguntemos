<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class MessageGenerator
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    // public function getHappyMessage(): string
    public function getHappyMessage(): void
    {
        $this->logger->info('About to find a happy message!');
        // ...
    }
}