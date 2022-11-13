<?php

namespace App\Tests\Unit\NF\Domain\Event;

use App\NF\Infrastructure\Event\EventDispatcher;
use App\NF\Infrastructure\Transports\DomainEvent\DomainEventBusInterface;
use App\Tests\Providers\NotificationProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;

/**
 * @group Unit
 * @group Infrastructure
 * @group uedis
 */
class EventDispatcherTest extends TestCase
{
    public function testPublisherEvent(): void
    {

        $notification = NotificationProvider::createNotificaton();
        $notification->clear();
        $event1 = NotificationProvider::createEmailEvent(__METHOD__);
        $event2 = NotificationProvider::createEmailEvent(__METHOD__);
        $notification->addEvent($event1);
        $notification->addEvent($event2);
        $this->assertSame(2, $notification->countEvents());

        /**
         * @var MockObject|DomainEventBusInterface
         */
        $messageBus = $this->getMockBuilder(DomainEventBusInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['dispatch'])
            ->getMockForAbstractClass();

        $messageBus->expects($this->exactly(2))->method('dispatch')
            ->willReturnOnConsecutiveCalls(new Envelope($event1), new Envelope($event1));

        $eventDispatcher = new EventDispatcher($messageBus);
        $eventDispatcher->dispatch($notification->getEvents());
    }
}
