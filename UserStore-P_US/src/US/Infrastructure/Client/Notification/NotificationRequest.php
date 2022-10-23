<?php

namespace App\US\Infrastructure\Client\Notification;

class NotificationRequest
{
    public function __construct(
        public readonly string $token,
        public readonly string $type,
        public readonly array $data
    ) {
    }
}
