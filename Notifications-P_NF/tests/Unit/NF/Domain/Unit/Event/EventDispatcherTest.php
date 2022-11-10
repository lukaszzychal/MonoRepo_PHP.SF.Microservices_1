<?php

namespace App\Unit\NF\Domain\Unit\Event;

use App\NF\Infrastructure\Event\EventDispatcher;
use App\Tests\Providers\NotificationProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
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
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects($this->once())->method('dispatch')->with($event1);


        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->dispatch($notification->getEvents());
    }
}
