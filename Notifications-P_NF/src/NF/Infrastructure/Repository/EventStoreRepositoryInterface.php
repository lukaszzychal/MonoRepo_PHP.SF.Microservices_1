<?php

namespace App\NF\Infrastructure\Repository;

use App\NF\Domain\Event\DomainEventInterface;
use Symfony\Component\Uid\Uuid;

interface EventStoreRepositoryInterface
{
    public function storeEvents(Uuid $uuid, string $source, array $events): void;
    public function store(Uuid $uuid, string $placeOccurrence, DomainEventInterface $event): void;
}
