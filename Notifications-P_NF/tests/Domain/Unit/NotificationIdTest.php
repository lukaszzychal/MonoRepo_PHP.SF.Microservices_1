<?php

namespace App\Tests\Domain\Unit;

use App\NF\Domain\Model\NotificationId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\InvalidArgumentException;


/**
 * @group unit
 * @group unid
 */
class NotificationIdTest extends TestCase
{
    public function testCreateWrongNotificationIdFromConstrutor(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No valid format ID');
        $notificationId = new NotificationId('wrong uuid');
    }

    public function testCreateCorectNotificationIdFromString(): void
    {
        $uuid = Uuid::v4();

        $notificationId = NotificationId::fromString((string) $uuid);
        $this->assertSame((string) $uuid, (string) $notificationId);
    }

    public function testCreateCorectNotificationIdFromUUID(): void
    {
        $uuid = Uuid::v4();

        $notificationId = NotificationId::fromUUID($uuid);
        $this->assertSame((string) $uuid, (string) $notificationId);
    }
}
