<?php

namespace App\NF\Infrastructure\Transports\DomainEvent;

use App\NF\Domain\Event\DomainEventInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DomainEventBus implements DomainEventBusInterface
{
    public function __construct(
        private MessageBusInterface $domainEventBus
    ) {
    }

    public function dispatch(DomainEventInterface $domainEvent): void
    {
        $this->domainEventBus->dispatch($domainEvent);
    }
}
