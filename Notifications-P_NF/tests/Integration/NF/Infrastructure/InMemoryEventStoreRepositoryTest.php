<?php

namespace App\Integration\NF\Infrastructure;

use App\NF\Domain\Enum\StatusEnum;
use App\NF\Domain\Event\EventLogs\EventLogsReadInterface;
use App\NF\Domain\Event\EventLogs\EventLogsWriteInterface;
use App\NF\Domain\Model\Notification;
use App\NF\Infrastructure\Repository\EventStoreRepositoryInterface;
use App\Tests\Providers\NotificationProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @group integration
 * @group infrastructure
 * @group iesr
 */
class InMemoryEventStoreRepositoryTest extends KernelTestCase
{
    public function testEventStore(): void
    {
        /**
         * @var Notification|EventLogsWriteInterface|EventLogsReadInterface $notification
         */
        $notification = NotificationProvider::createNotificaton();
        $this->assertSame(1, $notification->countEvents());

        /**
         * @var EventStoreRepositoryInterface
         */
        $eventStore = $this->getContainer()->get('test.InMemoryEventStoreRepositoryInterface');
        $uuid = Uuid::fromString($notification->getId());
        $eventStore->storeEvents(
            $uuid,
            get_class($notification),
            $notification->getEvents()
        );

        $this->assertSame(1, $eventStore->countEvents($uuid));
    }

    public function testEventStoreAgregate(): void
    {
        /**
         * @var Notification|EventLogsWriteInterface|EventLogsReadInterface $notification
         */
        $notification = NotificationProvider::createNotificaton();
        $this->assertSame(1, $notification->countEvents());
        $this->assertSame(StatusEnum::CREATED, $notification->getStatus());
        $notification->failedSent();
        $this->assertSame(StatusEnum::FAILED, $notification->getStatus());
        $this->assertSame(2, $notification->countEvents());

        /**
         * @var EventStoreRepositoryInterface
         */
        $eventStore = $this->getContainer()->get('test.InMemoryEventStoreRepositoryInterface');
        $uuid = Uuid::fromString($notification->getId());
        $eventStore->storeEvents(
            $uuid,
            get_class($notification),
            $notification->getEvents()
        );

        $this->assertSame(2, $eventStore->countEvents($uuid));

        /**
         * @var Notification
         */
        $notificationAgregade = $eventStore->aggregate($uuid, Notification::class);
        $this->assertSame(StatusEnum::FAILED, $notificationAgregade->getStatus());
    }
}
