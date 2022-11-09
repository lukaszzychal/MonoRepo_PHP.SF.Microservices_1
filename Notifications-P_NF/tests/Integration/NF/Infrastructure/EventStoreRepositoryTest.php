<?php

namespace App\Integration\NF\Infrastructure;

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
class EventStoreRepositoryTest extends KernelTestCase
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
        $eventStore = $this->getContainer()->get(EventStoreRepositoryInterface::class);
        $uuid = Uuid::fromString($notification->getId());
        $eventStore->storeEvents(
            $uuid,
            get_class($notification),
            $notification->getEvents()
        );

        $this->assertSame(1, $eventStore->countEvents($uuid));
        // $eventDispatcher->dispatch($notification->getEvents());
    }
}
