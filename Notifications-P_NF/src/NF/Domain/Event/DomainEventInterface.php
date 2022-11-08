<?php

namespace App\NF\Domain\Event;

use DateTimeImmutable;

interface DomainEventInterface
{
    public function getDateCalled(): DateTimeImmutable;
}
