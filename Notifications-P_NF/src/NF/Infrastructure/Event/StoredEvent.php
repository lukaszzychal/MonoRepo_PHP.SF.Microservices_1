<?php

namespace App\NF\Infrastructure\Event;

use App\NF\Domain\Event\DomainEventInterface;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class StoredEvent
{
    private DateTimeImmutable $occurredOn;

    public function __construct(
        private int $version,
        private Uuid $streamId,
        private string $placeOccurrence,
        private string $eventName,
        private DomainEventInterface $event
    ) {
        $this->occurredOn = new DateTimeImmutable();
    }
}
