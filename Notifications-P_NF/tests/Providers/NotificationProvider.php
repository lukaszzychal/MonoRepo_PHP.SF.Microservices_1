<?php

namespace App\Tests\Providers;

use App\NF\Domain\Enum\StatusEnum;
use App\NF\Domain\Enum\TypeEnum;
use App\NF\Domain\Event\CreatedNotificationEvent;
use App\NF\Domain\Model\DetailsNotification;
use App\NF\Domain\Model\EmailDetailsNotification;
use App\NF\Domain\Model\Notification;
use App\NF\Domain\Model\NotificationId;
use Symfony\Component\Uid\Uuid;

class NotificationProvider
{



    public static function createEmailDetails(
        string $from = 'from',
        string $to = 'to',
        string $subject = 'subject',
        string $body = 'body'
    ): EmailDetailsNotification {
        return new EmailDetailsNotification(
            $from,
            $to,
            $subject,
            $body
        );
    }

    public static function createEmailEvent(
        ?Uuid $uuid = null,
        ?TypeEnum $type = null,
        ?StatusEnum $status = null,
        ?DetailsNotification $details = null
    ): CreatedNotificationEvent {
        return new CreatedNotificationEvent(
            NotificationId::fromUUID($uuid ?: Uuid::v4()),
            $type ?: TypeEnum::EMAIL,
            $status ?:  StatusEnum::CREATED,
            $details ?: self::createEmailDetails()

        );
    }

    public static function createNotificaton(): Notification
    {
        return Notification::create(
            NotificationId::random(),
            TypeEnum::EMAIL,
            NotificationProvider::createEmailDetails()
        );
    }
}
