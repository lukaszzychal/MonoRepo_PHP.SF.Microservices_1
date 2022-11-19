<?php

namespace App\NF\Domain\Event;


use App\NF\Domain\Model\AggregateInterface;


interface DomainEventInterface
{
    public function getDateCalled(): \DateTimeImmutable;
    public function getObject(): AggregateInterface;
}
