<?php

declare(strict_types=1);

namespace App\US\Domain\User;

use Symfony\Component\Uid\Uuid;

final class UserID
{
    public function __construct(
        public readonly Uuid $uuid
    ) {
        Uuid::isValid($this->uuid);
    }

    public function __toString(): string
    {
        return (string) $this->uuid;
    }

    public static function fromString(string $uuid): UserID
    {
        return new self(Uuid::fromString($uuid));
    }
}
