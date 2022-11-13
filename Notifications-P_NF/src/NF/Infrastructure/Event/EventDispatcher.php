<?php

namespace App\NF\Infrastructure\Event;

use App\NF\Domain\Event\DomainEventInterface;
use App\NF\Infrastructure\Transports\DomainEvent\DomainEventBusInterface;

class EventDispatcher
{
    public function __construct(
        private DomainEventBusInterface $domainEventBus
    ) {
    }

    /**
     * @param DomainEventInterface[] $events
     */
    public function dispatch(array $events): void
    {
        foreach ($events as $event) {
            $this->domainEventBus->dispatch($event);
        }
    }
}
