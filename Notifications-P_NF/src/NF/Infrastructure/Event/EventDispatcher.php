<?php

namespace App\NF\Infrastructure\Event;

use App\NF\Domain\Event\DomainEventInterface;
use App\NF\Infrastructure\Transports\DomainEvent\DomainEventBusInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class EventDispatcher
{
    public function __construct(
        private DomainEventBusInterface $domainEventBus
    ) {
    }
    /**
     *
     * @param DomainEventInterface[] $events
     * @return void
     */
    public function dispatch(array $events): void
    {
        foreach ($events as $event) {
            $this->domainEventBus->dispatch($event);
        }
    }
}