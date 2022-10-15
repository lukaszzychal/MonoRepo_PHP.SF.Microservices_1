<?php

namespace App\US\Domain\User\Event;

use Symfony\Component\Uid\Uuid;

class UserCreated
{
    public function __construct(
        public readonly string $uuid
    ) {
        if (!Uuid::isValid($uuid)) {
            // throw new Exception("ToDO ", 1);
        }
    }
}
