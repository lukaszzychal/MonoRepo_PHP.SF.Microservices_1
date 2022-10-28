<?php

namespace App\US\Infrastructure\Client\Notification;

class NotificationRequest
{
    /**
     * @todo do przerobiienia polee data
     *
     * @param array<string> $data
     */
    public function __construct(
        public readonly string $token,
        public readonly string $type,
        public readonly array $data
    ) {
    }
}
