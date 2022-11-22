<?php

declare(strict_types=1);

namespace App\Tests\Providers;

use App\NF\Domain\Enum\StatusEnum;
use App\NF\Domain\Enum\TypeEnum;
use App\NF\Domain\Event\CreatedNotificationEvent;
use App\NF\Domain\Model\DetailsNotification;
use App\NF\Domain\Model\EmailDetailsNotification;
use App\NF\Domain\Model\Notification;
use App\NF\Domain\Model\NotificationId;
use Symfony\Component\Uid\Uuid;

// @todo add real data
// @todo add bulder
// @todo add multi create
// @todo add random
class NotificationProvider
{
    private const UUIDS = [];

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
        string $placeCreated,
        ?Uuid $uuid = null,
        ?TypeEnum $type = null,
        ?StatusEnum $status = null,
        ?DetailsNotification $details = null,
    ): CreatedNotificationEvent {
        $notif = NotificationProvider::createNotificaton();

        return new CreatedNotificationEvent(
            NotificationId::fromUUID($uuid ?: Uuid::v4()),
            $type ?: TypeEnum::EMAIL,
            $status ?: StatusEnum::CREATED,
            $details ?: self::createEmailDetails(),
            get_class($notif),
            $notif,
            $placeCreated
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
