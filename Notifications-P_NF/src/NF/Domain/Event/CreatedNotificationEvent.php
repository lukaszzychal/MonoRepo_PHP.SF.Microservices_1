<?php

declare(strict_types=1);

namespace App\NF\Domain\Event;

use App\NF\Domain\Enum\StatusEnum;
use App\NF\Domain\Enum\TypeEnum;
use DateTimeImmutable;

class CreatedNotificationEvent implements DomainEventInterface
{
    public DateTimeImmutable $date;
    public string $eventName;

    public function __construct(
        public readonly string $id,
        public readonly string $type,
        public readonly string $status
    ) {
        $this->date = new DateTimeImmutable();
        $this->eventName = (new \ReflectionClass($this))->getShortName();
    }
}
