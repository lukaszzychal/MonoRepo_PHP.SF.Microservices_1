<?php

namespace App\NF\Domain\Event\EventLogs;

interface EventLogsReadInterface
{
    /**
     * Return array Domain Events.
     *
     * @return array|DomainEventInterface[]
     *
     * @phpstan-ignore-next-line
     */
    public function getEvents(): array;

    public function hasEvents(): bool;

    public function countEvents(): int;

    public function clear(): void;
}
