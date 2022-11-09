<?php

namespace App\NF\Infrastructure\Event;

use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class EventStream
{
    public function __construct(
        private Uuid $id,
        private int $version,
        private ?DateTimeImmutable $updateAt = null,
    ) {
    }

    /**
     * Get the value of version
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    public function IncremetVersion(): void
    {
        $this->version++;
    }

    public function updateDate(DateTimeImmutable $updateAt): void
    {
        $this->updateAt = $updateAt;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }
}
