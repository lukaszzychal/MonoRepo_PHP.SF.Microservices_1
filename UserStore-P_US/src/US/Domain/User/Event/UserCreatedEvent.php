<?php

declare(strict_types=1);

namespace App\US\Domain\User\Event;

use Symfony\Component\Uid\Uuid;

final class UserCreatedEvent
{
    public function __construct(
        public readonly string $uuid,
    ) {
        if (!Uuid::isValid($uuid)) {
            // throw new Exception("ToDO ", 1);
        }
    }
}
