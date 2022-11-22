<?php

namespace App\NF\Domain\Event\EventLogs;

use App\NF\Domain\Event\DomainEventInterface;

interface EventLogsWriteInterface
{
    public function addEvent(DomainEventInterface $domainEvent): void;
}
