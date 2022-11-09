<?php

namespace App\NF\Domain\Event;

interface DomainEventInterface
{
    public function getDateCalled(): \DateTimeImmutable;
}
