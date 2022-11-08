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
    public DateTimeImmutable $date;
    public string $eventName;

    public function __construct(
        public readonly NotificationId $id,
        public readonly TypeEnum $type,
        public readonly StatusEnum $status,
        public readonly DetailsNotification $details
    ) {
        $this->date = new DateTimeImmutable();
        $this->eventName = (new \ReflectionClass($this))->getShortName();
    }
}
