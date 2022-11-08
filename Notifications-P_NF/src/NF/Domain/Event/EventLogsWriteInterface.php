<?php

namespace App\NF\Domain\Event;

interface EventLogsWriteInterface
{
    public function addEvent(DomainEventInterface $domainEvent): void;
}
