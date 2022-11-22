<?php

declare(strict_types=1);

namespace App\Tests\Unit\NF\Infrastructure;

use App\NF\Infrastructure\DomainEvent\DomainEventStore;
use App\Tests\Providers\NotificationProvider;
use PHPUnit\Framework\TestCase;

/**
 * @group Unit
 * @group udes
 */
class DomainEventStoreTest extends TestCase
{
    public function testEmptyStore(): void
    {
        $eventStore = DomainEventStore::getInstance();
        $eventStore->clear();
        $this->assertFalse($eventStore->hasEvents());
        $this->assertSame(0, $eventStore->countEvents());
    }

    public function testAddEventToStore(): void
    {
        $eventStore = DomainEventStore::getInstance();
        $event = NotificationProvider::createEmailEvent(__METHOD__);

        $eventStore->addEvent($event);

        $this->assertTrue($eventStore->hasEvents());
        $this->assertSame(1, $eventStore->countEvents());
    }

    public function testAddManyEventToStore(): void
    {
        $eventStore = DomainEventStore::getInstance();

        $event1 = NotificationProvider::createEmailEvent(__METHOD__);
        $event2 = NotificationProvider::createEmailEvent(__METHOD__);

        $eventStore->addManyEvent($event1, $event2);

        $this->assertTrue($eventStore->hasEvents());
        $this->assertSame(3, $eventStore->countEvents());
    }

    public function testAddArrayEventToStore(): void
    {
        $eventStore = DomainEventStore::getInstance();
        $events = [
                NotificationProvider::createEmailEvent(__METHOD__),
                NotificationProvider::createEmailEvent(__METHOD__),
        ];

        $eventStore->addArrayEvent($events);

        $this->assertTrue($eventStore->hasEvents());
        $this->assertSame(5, $eventStore->countEvents());
    }

    public function testClearStore(): void
    {
        $eventStore = DomainEventStore::getInstance();

        $eventStore->clear();

        $this->assertFalse($eventStore->hasEvents());
        $this->assertSame(0, $eventStore->countEvents());
    }
}
