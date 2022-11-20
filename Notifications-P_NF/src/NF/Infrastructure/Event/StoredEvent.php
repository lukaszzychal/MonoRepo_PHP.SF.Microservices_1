<?php

namespace App\NF\Infrastructure\Event;

use App\NF\Domain\Event\DomainEventInterface;
use Symfony\Component\Uid\Uuid;

class StoredEvent
{
    private \DateTimeImmutable $occurredOn;

    public function __construct(
        private int $version,
        private Uuid $streamId,
        private string $placeOccurrence,
        private string $eventName,
        private DomainEventInterface $event
    ) {
        $this->occurredOn = new \DateTimeImmutable();
    }

    /**
     * Get the value of event.
     */
    public function getEvent(): DomainEventInterface
    {
        return $this->event;
    }

    /**
     * Get the value of version.
     */
    public function getVersion(): int
    {
        return $this->version;
    }
}
