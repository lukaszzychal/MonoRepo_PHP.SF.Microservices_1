<?php

declare(strict_types=1);

namespace App\NF\Domain\Event;

trait EventLogs
{
    private array $events = [];

    public function addEvent(DomainEventInterface $domainEvent): void
    {
        $this->events[] = $domainEvent;
    }

    /**
     * Return array Domain Events
     *
     * @return DomainEventInterface[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    public function hasEvents(): bool
    {
        return 0 < $this->countEvents();
    }

    public function countEvents(): int
    {
        return count($this->events);
    }

    public function clear(): void
    {
        $this->events = [];
    }
}
