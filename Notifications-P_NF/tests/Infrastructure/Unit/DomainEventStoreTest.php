<?php

namespace App\Tests\Infrastructure\Unit;

use App\NF\Domain\Enum\StatusEnum;
use App\NF\Domain\Enum\TypeEnum;
use App\NF\Domain\Event\CreatedNotificationEvent;
use App\NF\Infrastructure\DomainEvent\DomainEventStore;
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
        $this->assertFalse($eventStore->hasEvents());
        $this->assertSame(0, $eventStore->countEvents());
    }

    public function testAddEventToStore(): void
    {
        $eventStore = DomainEventStore::getInstance();
        $event = new CreatedNotificationEvent(TypeEnum::EMAIL, StatusEnum::CREATE);

        $eventStore->addEvent($event);

        $this->assertTrue($eventStore->hasEvents());
        $this->assertSame(1, $eventStore->countEvents());
    }

    public function testAddManyEventToStore(): void
    {

        $eventStore = DomainEventStore::getInstance();
        $event1 = new CreatedNotificationEvent(TypeEnum::EMAIL, StatusEnum::CREATE);
        $event2 = new CreatedNotificationEvent(TypeEnum::EMAIL, StatusEnum::CREATE);

        $eventStore->addManyEvent($event1, $event2);

        $this->assertTrue($eventStore->hasEvents());
        $this->assertSame(3, $eventStore->countEvents());
    }

    public function testAddArrayEventToStore(): void
    {
        $eventStore = DomainEventStore::getInstance();
        $events = [
            new CreatedNotificationEvent(TypeEnum::EMAIL, StatusEnum::CREATE),
            new CreatedNotificationEvent(TypeEnum::EMAIL, StatusEnum::CREATE)
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
