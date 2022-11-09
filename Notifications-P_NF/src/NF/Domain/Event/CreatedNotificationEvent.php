<?php

declare(strict_types=1);

namespace App\NF\Domain\Event;

use App\NF\Domain\Enum\StatusEnum;
use App\NF\Domain\Enum\TypeEnum;
use App\NF\Domain\Model\DetailsNotification;
use App\NF\Domain\Model\NotificationId;
use DateTimeImmutable;

class CreatedNotificationEvent implements DomainEventInterface
{
    public DateTimeImmutable $dateCalled;
    public string $eventName;

    public function __construct(
        public readonly NotificationId $id,
        public readonly TypeEnum $type,
        public readonly StatusEnum $status,
        public readonly DetailsNotification $details,
        public readonly string $placeOccurrence
    ) {
        $this->dateCalled = new DateTimeImmutable();
        $this->eventName = (new \ReflectionClass($this))->getShortName();
    }

    public function getDateCalled(): DateTimeImmutable
    {
        return $this->dateCalled;
    }
}
