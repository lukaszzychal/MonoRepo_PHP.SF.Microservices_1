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
    private array $eventStore = [];

    public function __construct(
        /**
         * @todo change to inteerface
         *
         * @var InMemoryEventStreamReppository
         */
        private readonly InMemoryEventStreamReppository $eventStreamRepository
    ) {
    }

    public function storeEvents(Uuid $uuid, string $source, array $events): void
    {
        foreach ($events as $event) {
            $this->store($uuid, $source, $event);
        }
    }

    public function store(Uuid $uuid, string $placeOccurrence, DomainEventInterface $event): void
    {

        $version = 0;
        $stream = $this->getStream($uuid);
        $version = $stream->getVersion();

        $storedEvent = new StoredEvent(
            ++$version,
            $stream->getId(),
            $placeOccurrence,
            get_class($event),
            $event
        );
        $stream->IncremetVersion();
        $stream->updateDate($event->getDateCalled());
        $this->eventStore[] = $storedEvent;
    }

    private function getStream(Uuid $uuid): EventStream
    {
        if ($this->eventStreamRepository->exist($uuid)) {
            return $this->eventStreamRepository->get($uuid);
        }

        return $this->eventStreamRepository->create($uuid);
    }
}
