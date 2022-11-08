<?php

namespace App\NF\Domain\Event;

interface EventLogsReadInterface
{
     /**
      * Return array Domain Events.
      *
      * @return DomainEventInterface[]
      */
     public function getEvents(): array;

     public function hasEvents(): bool;

     public function countEvents(): int;

     public function clear(): void;
}
