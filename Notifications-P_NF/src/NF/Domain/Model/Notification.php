<?php

declare(strict_types=1);

namespace App\NF\Domain\Model;

use App\NF\Domain\Enum\StatusEnum;
use App\NF\Domain\Enum\TypeEnum;
use App\NF\Domain\Event\CreatedNotificationEvent;
use App\NF\Domain\Event\DomainEventInterface;
use App\NF\Domain\Event\EventLogs\EventLogsTrait;
use App\NF\Domain\Event\EventLogs\EventLogsWriteInterface;
use App\NF\Domain\Event\FailedSentNotificationEvent;
use App\NF\Domain\Event\NotificationDomainEventInterface;
use App\NF\Infrastructure\Event\StoredEvent;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

// #[DiscriminatorMap(typeProperty: 'type', mapping: [
//     'email' => NotificationEmail::class,
// ])]
class Notification implements AggregateInterface, EventLogsWriteInterface
{
    use EventLogsTrait;

    private StatusEnum $status;

    private function __construct(
        private readonly NotificationId $id,
        private readonly TypeEnum $type,
        private readonly DetailsNotification $detailsNotification
    ) {
        $this->status = StatusEnum::CREATED;
        $this->addEvent(
            new CreatedNotificationEvent(
                $this->id,
                $this->type,
                $this->status,
                $this->detailsNotification,
                get_class($this),
                $this,
                __METHOD__
            )
        );
    }

    public static function create(
        NotificationId $id,
        TypeEnum $type,
        DetailsNotification $detailsNotification
    ): self {
        return new self($id, $type, $detailsNotification);
    }

    public function send(): void
    {
        $this->status = StatusEnum::SENT;
    }

    public function failedSent(): void
    {
        $this->status = StatusEnum::FAILED;
        $this->addEvent(
            new FailedSentNotificationEvent(
                $this->id,
                $this->type,
                $this->status,
                $this->detailsNotification,
                get_class($this),
                $this,
                __METHOD__
            )
        );
    }

    public function reSent(): void
    {
        if ($this->status == StatusEnum::FAILED->value) {
            $this->status = StatusEnum::FAILED;
        }
    }

    /**
     * Get the value of id.
     */
    public function getId(): NotificationId
    {
        return $this->id;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): StatusEnum
    {
        return $this->status;
    }

    // /**
    //  * @todo zrobić osobny service
    //  *
    //  * @param DomainEventInterface $domainEvent
    //  * @return void
    //  */
    // public  function apply(DomainEventInterface $domainEvent): void
    // {
    //     $this->id = NotificationId::fromUUID($domainEvent->getId());
    //     $this->status = $domainEvent->getStatus();
    //     $this->type = $domainEvent->getType();
    //     $this->detailsNotification = $domainEvent->getDetails();
    // }
}
