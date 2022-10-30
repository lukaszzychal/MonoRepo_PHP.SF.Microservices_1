<?php

namespace App\Domain\Event;

use App\NF\Domain\Enum\StatusEnum;
use App\NF\Domain\Enum\TypeEnum;
use App\NF\Domain\Event\CreatedNotificationEvent;
use App\NF\Domain\Event\EventLogs;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 * @group uel
 */
class EventLogsTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }



    public function testEmpty()
    {
        $trait = new class
        {
            use EventLogs;
        };

        $this->assertEventLogs($trait, 0);
        $this->assertFalse($trait->hasEvents());

        return $trait;
    }

    /**
     * @depends testEmpty
     *
     */
    public function testAddOneEvent($trait)
    {
        $event = new CreatedNotificationEvent(TypeEnum::EMAIL, StatusEnum::CREATE);

        $trait->addEvent($event);

        $this->assertEventLogs($trait, 1);
        $this->assertTrue($trait->hasEvents());

        return $trait;
    }

    /**
     * @depends testAddOneEvent
     *
     */
    public function testAddTwoEvent($trait)
    {
        $event = new CreatedNotificationEvent(TypeEnum::EMAIL, StatusEnum::SENT);

        $trait->addEvent($event);

        $this->assertEventLogs($trait, 2);
        $this->assertTrue($trait->hasEvents());

        return $trait;
    }

    /**
     * @depends testAddTwoEvent
     *
     */
    public function testClearEvent($trait): void
    {
        $trait->clear();
        $this->assertEventLogs($trait, 0);
        $this->assertFalse($trait->hasEvents());
    }

    public function assertEventLogs($trait, int $count = 0)
    {
        $this->assertSame($count, $trait->countEvents());
        $this->assertIsArray($trait->getEvents());
        $this->assertSame($count, count($trait->getEvents()));
        $this->assertSame($count, $trait->countEvents());
    }
}
