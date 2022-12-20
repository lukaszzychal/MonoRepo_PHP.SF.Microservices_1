<?php

declare(strict_types=1);

namespace App\NF\Domain\Model;

use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

final class NotificationId
{
    public readonly string $uuid;

    public function __construct(string $uuid)
    {
        try {
            Assert::uuid($uuid, 'No valid format ID');
        } catch (InvalidArgumentException $th) {
            // Dodddać własny wyjąctek domeeny
            throw $th;
        }

        $this->uuid = $uuid;
    }

    public static function fromString(string $uuid): self
    {
        return new self($uuid);
    }

    public static function fromUUID(Uuid $uuid): self
    {
        return new self((string) $uuid);
    }

    public function asUuid(): Uuid
    {
        return Uuid::fromString($this->uuid);
    }

    public static function random(): self
    {
        return self::fromUUID(
            Uuid::v4()
        );
    }

    public function __toString()
    {
        return $this->uuid;
    }
}
