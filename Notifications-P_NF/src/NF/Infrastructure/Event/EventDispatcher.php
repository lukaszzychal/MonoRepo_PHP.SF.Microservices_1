<?php

namespace App\NF\Infrastructure\Event;

use App\NF\Domain\Event\DomainEventInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class EventDispatcher
{
    public function __construct(
        private MessageBusInterface $mssageBus
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
            $this->mssageBus->dispatch($event);
        }
    }
}
