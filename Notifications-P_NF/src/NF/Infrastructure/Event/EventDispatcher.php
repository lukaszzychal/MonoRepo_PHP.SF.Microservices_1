<?php

namespace App\NF\Infrastructure\Event;

use App\NF\Domain\Event\DomainEventInterface;

class EventDispatcher
{
    /**
     *
     * @param DomainEventInterface[] $events
     * @return void
     */
    public function dispatch(array $events): void
    {
    }
}
