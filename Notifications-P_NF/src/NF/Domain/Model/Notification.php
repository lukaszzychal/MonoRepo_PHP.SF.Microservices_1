<?php

declare(strict_types=1);

namespace App\NF\Domain\Model;

use App\NF\Domain\Enum\StatusEnum;
use App\NF\Domain\Enum\TypeEnum;
use App\NF\Domain\Event\CreatedNotificationEvent;
use App\NF\Domain\Event\EventLogsTrait;
use App\NF\Domain\Event\EventLogsWriteInterface;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;


#[DiscriminatorMap(typeProperty: 'type', mapping: [
    'email' => NotificationEmail::class,
])]
class Notification implements EventLogsWriteInterface
{
    use EventLogsTrait;

    private readonly StatusEnum $status;

    public function __construct(
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
                $this->detailsNotification
            )
        );
    }

    public function send(): void
    {
        $this->status = StatusEnum::SENT;
    }

    public function failedSent(): void
    {
        $this->status = StatusEnum::FAILED;
    }

    public function reSent(): void
    {
        if ($this->status == StatusEnum::FAILED->value) {
            $this->status = StatusEnum::FAILED;
        }
    }
}
