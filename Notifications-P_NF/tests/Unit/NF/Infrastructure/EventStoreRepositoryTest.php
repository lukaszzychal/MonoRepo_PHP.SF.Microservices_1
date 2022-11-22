<?php

namespace App\Tests\Unit\NF\Infrastructure;

use App\NF\Domain\Enum\StatusEnum;
use App\NF\Domain\Event\EventLogs\EventLogsReadInterface;
use App\NF\Domain\Event\EventLogs\EventLogsWriteInterface;
use App\NF\Domain\Model\Notification;
use App\NF\Infrastructure\Event\EventStream;
use App\NF\Infrastructure\Repository\EventStoreRepositoryInterface;
use App\NF\Infrastructure\Repository\EventStreamRepositoryInterface;
use App\Tests\Providers\NotificationProvider;
use App\Tests\TestDouble\FakeEventStoreRepository;
use PHPUnit\Framework\MockObject\Stub as MockObjectStub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @group unit
 * @group infrastructure
 * @group uesr
 */
class EventStoreRepositoryTest extends TestCase
{
    public function testEventStore(): void
    {
        /**
         * @var Notification|EventLogsWriteInterface|EventLogsReadInterface $notification
         */
        $notification = NotificationProvider::createNotificaton();
        $this->assertSame(1, $notification->countEvents());

        $uuid = Uuid::fromString($notification->getId());
        /**
         * @var MockObjectStub|EventStreamRepositoryInterface $eventStreamRepositoryStub
         */
        $eventStreamRepositoryStub = $this->createEventStreamRepositoryStub($uuid);

        /**
         * @var EventStoreRepositoryInterface $eventStore
         */
        $eventStore = new FakeEventStoreRepository($eventStreamRepositoryStub);

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

        $uuid = Uuid::fromString($notification->getId());

        /**
         * @var MockObjectStub|EventStreamRepositoryInterface $eventStreamRepositoryStub
         */
        $eventStreamRepositoryStub = $this->createEventStreamRepositoryStub($uuid);

        /**
         * @var EventStoreRepositoryInterface
         */
        $eventStore = new FakeEventStoreRepository($eventStreamRepositoryStub);
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

    private function createEventStreamRepositoryStub(Uuid $uuid): MockObjectStub|EventStreamRepositoryInterface
    {
        /**
         * @var MockObjectStub|EventStreamRepositoryInterface $eventStreamRepositoryStub
         */
        $eventStreamRepositoryStub = $this->createStub(EventStreamRepositoryInterface::class);

        $es = EventStream::createEmptyStream($uuid);
        $eventStreamRepositoryStub->method('exist')->willReturn(false);
        $eventStreamRepositoryStub->method('create')->willReturn($es);

        return $eventStreamRepositoryStub;
    }
}
