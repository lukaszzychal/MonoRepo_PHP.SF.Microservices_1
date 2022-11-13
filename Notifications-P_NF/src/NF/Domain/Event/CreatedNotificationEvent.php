<?php

declare(strict_types=1);

namespace App\NF\Domain\Event;

use App\NF\Domain\Enum\StatusEnum;
use App\NF\Domain\Enum\TypeEnum;
use App\NF\Domain\Model\DetailsNotification;
use App\NF\Domain\Model\NotificationId;

class CreatedNotificationEvent implements DomainEventInterface
{
    public \DateTimeImmutable $dateCalled;
    public string $eventName;
    public string $placeOccurrence;
    public string $placeCreated;
    public function __construct(
        public readonly NotificationId $id,
        public readonly TypeEnum $type,
        public readonly StatusEnum $status,
        public readonly DetailsNotification $details,
        string $placeCreated = __METHOD__
    ) {
        $this->dateCalled = new \DateTimeImmutable();
        $this->eventName = (new \ReflectionClass($this))->getShortName();
        $this->placeCreated = $placeCreated ?? __METHOD__;
        $this->placeOccurrence = static::class;
    }

    public function getDateCalled(): \DateTimeImmutable
    {
        return $this->dateCalled;
    }
}
