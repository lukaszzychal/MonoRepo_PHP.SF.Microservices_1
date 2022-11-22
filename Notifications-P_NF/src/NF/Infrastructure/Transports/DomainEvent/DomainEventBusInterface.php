<?php

namespace App\NF\Infrastructure\Transports\DomainEvent;

use App\NF\Domain\Event\DomainEventInterface;

interface DomainEventBusInterface
{
    public function dispatch(DomainEventInterface $domainEvent): void;
}
