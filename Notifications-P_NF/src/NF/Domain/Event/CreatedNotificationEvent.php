<?php

declare(strict_types=1);

namespace App\NF\Domain\Event;

use App\NF\Domain\Enum\StatusEnum;
use App\NF\Domain\Enum\TypeEnum;
use App\NF\Domain\Model\AggregateInterface;
use App\NF\Domain\Model\DetailsNotification;
use App\NF\Domain\Model\NotificationId;
use Symfony\Component\Uid\Uuid;

class CreatedNotificationEvent implements NotificationDomainEventInterface
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
        public readonly string $class,
        public readonly AggregateInterface $notification,
        string $placeCreated = __METHOD__
    ) {
        $this->dateCalled = new \DateTimeImmutable();
        $this->eventName = (new \ReflectionClass($this))->getShortName();
        $this->placeCreated = $placeCreated;
        $this->placeOccurrence = static::class;
    }

    public function getDateCalled(): \DateTimeImmutable
    {
        return $this->dateCalled;
    }

    public function getDetailsNotification(): DetailsNotification
    {
        return $this->details;
    }

    public function getId(): Uuid
    {
        return Uuid::fromString((string) $this->id);
    }
    public function getType(): TypeEnum
    {
        return $this->type;
    }
    public function getStatus(): StatusEnum
    {
        return $this->status;
    }
    public function getDetails(): DetailsNotification
    {
        return $this->details;
    }

    public function getObject(): AggregateInterface
    {
        return $this->notification;
    }
}
