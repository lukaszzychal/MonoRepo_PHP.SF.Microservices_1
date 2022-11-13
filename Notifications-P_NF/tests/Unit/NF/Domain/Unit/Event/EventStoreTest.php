<?php

declare(strict_types=1);

namespace App\Unit\NF\Domain\Unit\Event;

use App\NF\Domain\Model\Notification;
use App\NF\Domain\Model\NotificationId;
use App\NF\Infrastructure\Event\EventStream;
use App\NF\Infrastructure\Repository\EventStoreRepositoryInterface;
use App\NF\Infrastructure\Repository\EventStreamRepositoryInterface;
use App\NF\Infrastructure\Repository\InMemoryEventStoreRepository;
use App\Tests\Providers\NotificationProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @group Unit
 * @group Infrastructure
 * @group ues
 */
class EventStoreTest extends TestCase
{
    private const UUID = '50180b33-cc54-47d1-a193-5d26f5ddd982';

    /**
     * @dataProvider RepoProvident
     */
    public function testEventsStore(EventStoreRepositoryInterface $eventStore): void
    {
        /**
         * @var Notification|MockObject
         */
        $notificationMock = $this
            ->getMockBuilder(Notification::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getId', 'getEvents'])
            ->getMockForAbstractClass();
        $notificationMock->expects($this->once())->method('getId')
            ->willReturn(NotificationId::fromString(self::UUID));
        $notificationMock->expects($this->once())->method('getEvents')
            ->willReturn([
                NotificationProvider::createEmailEvent(),
            ]);
        $uuid = Uuid::fromString((string) $notificationMock->getId());

        $eventStore->storeEvents(
            $uuid,
            get_class($notificationMock),
            $notificationMock->getEvents()
        );

        $this->assertSame(1, $eventStore->countEvents($uuid));
    }

    public function RepoProvident(): array
    {
        /**
         * @var EventStreamRepositoryInterface|MockObject
         */
        $eventStreamReppositoryMock = $this->createMock(EventStreamRepositoryInterface::class);
        $eventStreamReppositoryMock
            ->expects($this->once())
            ->method('exist')
            ->willReturn(true);
        $eventStreamReppositoryMock
            ->expects($this->once())
            ->method('get')
            ->willReturn(EventStream::createEmptyStream(Uuid::fromString(self::UUID)));

        return [
            [new InMemoryEventStoreRepository(
                $eventStreamReppositoryMock
            )],
        ];
    }
}
