<?php

namespace App\Unit\NF\Domain\Unit\Event;

use App\NF\Infrastructure\Event\EventDispatcher;
use App\Tests\Providers\NotificationProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

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
        $event1 = NotificationProvider::createEmailEvent();
        $event2 = NotificationProvider::createEmailEvent();
        $notification->addEvent($event1);
        $this->assertSame(1, $notification->countEvents());

        /**
         * @var MockObject|MessageBusInterface
         */
        $messageBus = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['dispatch'])
            ->getMockForAbstractClass();

        $messageBus->expects($this->once())->method('dispatch')->willReturn(new Envelope($event1));

        $eventDispatcher = new EventDispatcher($messageBus);
        $eventDispatcher->dispatch($notification->getEvents());
    }
}
