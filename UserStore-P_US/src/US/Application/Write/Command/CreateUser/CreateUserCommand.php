<?php

namespace App\US\Application\Write\Command\CreateUser;

use Symfony\Component\Uid\Uuid;

class CreateUserCommand
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email
    ) {
        if (!Uuid::isValid($uuid)) {
            // throw new Exception("ToDO ", 1);
        }
    }
}
