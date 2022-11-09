<?php

namespace App\NF\Infrastructure\Repository;

use App\NF\Domain\Event\DomainEventInterface;
use App\NF\Infrastructure\Event\EventStream;
use Symfony\Component\Uid\Uuid;

interface EventStoreRepositoryInterface
{
    /**
     * @param DomainEventInterface[] $events
     */
    public function storeEvents(Uuid $uuid, string $source, array $events): void;

    public function store(EventStream $stream, string $placeOccurrence, DomainEventInterface $event): void;

    public function countEvents(Uuid $uuid): int;

    /**
     * @return DomainEventInterface[]
     */
    public function getEvents(Uuid $uuid): array;
}
