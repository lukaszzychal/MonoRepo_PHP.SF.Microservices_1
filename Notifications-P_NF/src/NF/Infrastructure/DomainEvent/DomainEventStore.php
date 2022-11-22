<?php

declare(strict_types=1);

namespace App\NF\Infrastructure\DomainEvent;

use App\NF\Domain\Event\DomainEventInterface;

final class DomainEventStore
{
    private static ?DomainEventStore $instances = null;
    private array $events = [];

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize a singleton.');
    }

    public static function getInstance(): self
    {
        if (is_null(self::$instances)) {
            self::$instances = new self();
        }

        return self::$instances;
    }

    public function addEvent(DomainEventInterface $event): void
    {
        $this->events[spl_object_hash($event)] = $event;
    }

    /**
     * @param DomainEventInterface[] $events
     */
    public function addArrayEvent(array $events = []): void
    {
        foreach ($events as $event) {
            $this->events[spl_object_hash($event)] = $event;
        }
    }

    public function addManyEvent(DomainEventInterface ...$events): void
    {
        foreach ($events as $event) {
            $this->events[spl_object_hash($event)] = $event;
        }
    }

    /**
     * @return DomainEventInterface[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    public function countEvents(): int
    {
        return count($this->events);
    }

    public function hasEvents(): bool
    {
        return !empty($this->events);
    }

    public function clear(): void
    {
        $this->events = [];
    }
}
