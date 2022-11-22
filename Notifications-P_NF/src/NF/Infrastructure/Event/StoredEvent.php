<?php

namespace App\NF\Infrastructure\Event;

use App\NF\Domain\Event\DomainEventInterface;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'events', schema: 'notification')]
class StoredEvent
{
    private \DateTimeImmutable $occurredOn;

    public function __construct(
        #[ORM\Column]
        private int $version,
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private Uuid $streamId,
        #[ORM\Column]
        private string $placeOccurrence,
        #[ORM\Column]
        private string $eventName,
        #[ORM\Column(type: 'json', options: ['jsonb' => true])]
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
