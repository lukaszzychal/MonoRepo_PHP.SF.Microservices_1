<?php

namespace App\NF\Infrastructure\Event;

use App\NF\Domain\Event\DomainEventInterface;
use App\NF\Infrastructure\Repository\EventStoreRepositoryInterface;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'event_stream', schema: 'notification')]
class EventStream
{
    private array $events = [];

    private function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private Uuid $id,

        #[ORM\Column]
        private int $eventNumber = 0,

        #[ORM\Column]
        private ?\DateTimeImmutable $updateAt = null,
    ) {
    }

    public static function create(Uuid $id): self
    {
        return new self($id, 0, new DateTimeImmutable());
    }



    public function IncremetVersion(): void
    {
        ++$this->eventNumber;
    }

    public function updateDate(\DateTimeImmutable $updateAt): void
    {
        $this->updateAt = $updateAt;
    }

    /**
     * Get the value of id.
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return DomainEventInterface[]
     */
    public function getEvents(EventStoreRepositoryInterface $eventStoreRepository): array
    {
        if (empty($this->events)) {
            $this->events = $eventStoreRepository->getEvents($this->getId());
        }

        return $this->events;
    }

    public static function createEmptyStream(Uuid $uuid): EventStream
    {
        return new self($uuid, 0, null);
    }

    /**
     * Get the value of eventNumber
     */
    public function getEventNumber()
    {
        return $this->eventNumber;
    }
}
