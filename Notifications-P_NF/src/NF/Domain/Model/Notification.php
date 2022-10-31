<?php

declare(strict_types=1);

namespace App\NF\Domain\Model;

use App\NF\Domain\Enum\StatusEnum;
use App\NF\Domain\Enum\TypeEnum;
use App\NF\Domain\Event\CreatedNotificationEvent;
use App\NF\Domain\Event\EventLogsTrait;

class Notification
{
    use EventLogsTrait;

    private readonly StatusEnum $status;

    public function __construct(
        private readonly NotificationId $id,
        private readonly TypeEnum $type
    ) {
        $this->status = StatusEnum::CREATE;
        $this->addEvent(new CreatedNotificationEvent((string) $this->id, $this->type->value, $this->status->value));
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
