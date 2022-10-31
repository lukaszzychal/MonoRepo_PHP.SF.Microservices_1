<?php

namespace App\Domain\Event;

use App\NF\Domain\Enum\StatusEnum;
use App\NF\Domain\Enum\TypeEnum;
use App\NF\Domain\Event\CreatedNotificationEvent;
use App\NF\Domain\Event\EventLogsTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @group unit
 * @group uel
 */
class EventLogsTest extends TestCase
{
    private Uuid $uuid;
    protected function setUp(): void
    {
        parent::setUp();

        $this->uuid = Uuid::v4();
    }

    public function testEmpty()
    {
        $trait = new class()
        {
            use EventLogsTrait;
        };


        $this->assertEventLogs($trait, 0);
        $this->assertFalse($trait->hasEvents());

        return $trait;
    }

    /**
     * @depends testEmpty
     */
    public function testAddOneEvent($trait)
    {
        $event = new CreatedNotificationEvent((string) $this->uuid, TypeEnum::EMAIL->value, StatusEnum::CREATE->value);

        $trait->addEvent($event);

        $this->assertEventLogs($trait, get_class($event), 1);
        $this->assertTrue($trait->hasEvents());

        return $trait;
    }

    /**
     * @depends testAddOneEvent
     */
    public function testAddTwoEvent($trait)
    {
        $event = new CreatedNotificationEvent((string) $this->uuid, TypeEnum::EMAIL->value, StatusEnum::SENT->value);

        $trait->addEvent($event);

        $this->assertEventLogs($trait, get_class($event), 2);
        $this->assertTrue($trait->hasEvents());

        return $trait;
    }

    /**
     * @depends testAddTwoEvent
     */
    public function testClearEvent($trait): void
    {
        $trait->clear();
        $this->assertEventLogs($trait, 0);
        $this->assertFalse($trait->hasEvents());
    }

    public function assertEventLogs($trait, string $className = '', int $count = 0)
    {
        $this->assertSame($count, $trait->countEvents());
        $this->assertIsArray($trait->getEvents());
        $this->assertSame($count, count($trait->getEvents()));
        $this->assertSame($count, $trait->countEvents());
        if ($trait->countEvents() > 0) {
            $shortName = $this->getShortNameClass($className);
            $this->assertSame($shortName, $trait->getEvents()[0]->eventName);
        }
    }

    private function getShortNameClass($object)
    {
        return (new \ReflectionClass($object))->getShortName();
    }
}
