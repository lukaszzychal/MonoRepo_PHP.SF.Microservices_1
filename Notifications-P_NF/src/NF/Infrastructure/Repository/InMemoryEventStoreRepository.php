<?php

namespace App\NF\Infrastructure\Repository;

use App\NF\Domain\Event\DomainEventInterface;
use App\NF\Infrastructure\Event\EventStream;
use App\NF\Infrastructure\Event\StoredEvent;
use Symfony\Component\Uid\Uuid;

class InMemoryEventStoreRepository implements EventStoreRepositoryInterface
{
    /**
     * Undocumented variable
     *
     * @var array[
     * striing => StoredEvent
     * ]
     */
    private static array $eventStore = [];
    private ?EventStream $stream = null;

    public function __construct(
        /**
         * @todo change to inteerface
         *
         * @var InMemoryEventStreamReppository
         */
        private readonly EventStreamRepositoryInterface $eventStreamRepository
    ) {
    }

    public function storeEvents(Uuid $uuid, string $source, array $events): void
    {
        $this->stream = $this->getStream($uuid);
        foreach ($events as $event) {
            $this->store($this->stream, $source, $event);
        }
    }

    public function store(EventStream $stream, string $placeOccurrence, DomainEventInterface $event): void
    {
        $version = $stream->getVersion();
        $stream->IncremetVersion();
        $initVersion = $stream->getVersion();

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
            $this->stream =  $this->getStream($uuid);
        }
        return $this->stream->getVersion();
    }

    public function getEvents(Uuid $uuid): array
    {
        if (is_null($this->stream)) {
            $this->stream =  $this->getStream($uuid);
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
}
