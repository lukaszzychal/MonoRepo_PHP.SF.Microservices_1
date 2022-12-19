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
use App\NF\Domain\Event\SentNotificationEvent;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
#[ORM\Table(name: 'notification', schema: 'notification')]
class Notification implements AggregateInterface, EventLogsWriteInterface
{
    use EventLogsTrait;

    #[ORM\Column()]
    private StatusEnum $status;

    private function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid')]
        private readonly Uuid $id,

        #[ORM\Column()]
        private readonly TypeEnum $type,
        #[ORM\Column(type: 'json', options: ['jsonb' => true])]
        private readonly DetailsNotification $detailsNotification
    ) {
        $this->status = StatusEnum::CREATED;
        $this->addEvent(
            new CreatedNotificationEvent(
                NotificationId::fromUUID($this->id),
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
        return new self($id->asUuid(), $type, $detailsNotification);
    }

    public function send(): void
    {
        $this->status = StatusEnum::SENT;
        $this->addEvent(
            new SentNotificationEvent(
                NotificationId::fromUUID($this->id),
                $this->type,
                $this->status,
                $this->detailsNotification,
                get_class($this),
                $this,
                __METHOD__
            )
        );
    }

    public function failedSent(): void
    {
        $this->status = StatusEnum::FAILED;
        $this->addEvent(
            new FailedSentNotificationEvent(
                NotificationId::fromUUID($this->id),
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
        return NotificationId::fromUUID($this->id);
    }

    /**
     * Get the value of status.
     */
    public function getStatus(): StatusEnum
    {
        return $this->status;
    }
}
