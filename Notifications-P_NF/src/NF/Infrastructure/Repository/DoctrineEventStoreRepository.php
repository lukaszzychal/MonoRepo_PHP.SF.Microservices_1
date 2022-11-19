<?php

namespace App\NF\Infrastructure\Repository;

use App\NF\Domain\Event\DomainEventInterface;
use App\NF\Domain\Model\Aggregate;
use App\NF\Domain\Model\AggregateInterface;
use App\NF\Infrastructure\Event\EventStream;
use App\NF\Infrastructure\Event\StoredEvent;
use App\Tests\Providers\NotificationProvider;
use Symfony\Component\Uid\Uuid;

class DoctrineEventStoreRepository implements EventStoreRepositoryInterface
{
    private static array $eventStore = [];
    private ?EventStream $stream = null;

    public function __construct(
        /**
         *
         * @var EventStreamRepositoryInterface
         */
        private readonly EventStreamRepositoryInterface $eventStreamRepository
    ) {
    }

    /**
     * @param DomainEventInterface[] $events
     */
    public function storeEvents(Uuid $uuid, string $source, array $events): void
    {
        /*
         * @var EventStream
         */
        $this->stream = $this->getStream($uuid);
        if (is_null($this->stream)) {
            throw new \Exception('Not found stream');
        }
        foreach ($events as $event) {
            //  @phpstan-ignore-next-line
            $this->store($this->stream, $source, $event);
        }
    }

    public function store(EventStream $stream, string $placeOccurrence, DomainEventInterface $event): void
    {
        $version = $stream->getEventNumber();
        $stream->IncremetVersion();
        $initVersion = $stream->getEventNumber();

        $storedEvent = new StoredEvent(
            $initVersion,
            $stream->getId(),
            $placeOccurrence,
            get_class($event),
            $event
        );

        $stream->updateDate($event->getDateCalled());

        self::$eventStore[(string) $stream->getId()][] = $storedEvent;
    }

    public function countEvents(Uuid $uuid): int
    {
        if (is_null($this->stream)) {
            $this->stream = $this->getStream($uuid);
        }

        return $this->stream->getEventNumber();
    }

    public function getEvents(Uuid $uuid): array
    {
        if (is_null($this->stream)) {
            $this->stream = $this->getStream($uuid);
        }

        return self::$eventStore[(string) $this->stream->getId()] ?? [];
    }

    private function getStream(Uuid $uuid): EventStream
    {
        if ($this->eventStreamRepository->exist($uuid)) {
            return $this->eventStreamRepository->get($uuid);
        }

        return $this->eventStreamRepository->create($uuid);
    }
    public function aggregate(Uuid $uuid, string $className): AggregateInterface
    {
        return NotificationProvider::createNotificaton();
    }
}
