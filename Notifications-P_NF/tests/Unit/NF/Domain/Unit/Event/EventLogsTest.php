<?php

declare(strict_types=1);

namespace App\Tests\Unit\NF\Domain\Unit\Event;

use App\NF\Domain\Enum\StatusEnum;
use App\NF\Domain\Enum\TypeEnum;
use App\NF\Domain\Event\CreatedNotificationEvent;
use App\NF\Domain\Event\EventLogsReadInterface;
use App\NF\Domain\Event\EventLogsTrait;
use App\NF\Domain\Event\EventLogsWriteInterface;
use App\NF\Domain\Model\EmailDetailsNotification;
use App\NF\Domain\Model\NotificationId;
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

        $this->emailDetails = new EmailDetailsNotification(
            'from',
            'to',
            'subject',
            'body'
        );
    }

    public function testEmpty(): EventLogsReadInterface|EventLogsWriteInterface
    {
        $trait = new class() implements EventLogsReadInterface, EventLogsWriteInterface
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
    public function testAddOneEvent(EventLogsReadInterface|EventLogsWriteInterface $trait): EventLogsReadInterface|EventLogsWriteInterface
    {
        $event = new CreatedNotificationEvent(
            NotificationId::fromUUID($this->uuid),
            TypeEnum::EMAIL,
            StatusEnum::CREATED,
            $this->emailDetails
        );

        $trait->addEvent($event);

        $this->assertEventLogs($trait, 1, get_class($event));
        $this->assertTrue($trait->hasEvents());

        return $trait;
    }

    /**
     * @depends testAddOneEvent
     */
    public function testAddTwoEvent(EventLogsReadInterface|EventLogsWriteInterface $trait): EventLogsReadInterface|EventLogsWriteInterface
    {
        $event = new CreatedNotificationEvent(
            NotificationId::fromUUID($this->uuid),
            TypeEnum::EMAIL,
            StatusEnum::SENT,
            $this->emailDetails
        );

        $trait->addEvent($event);

        $this->assertEventLogs($trait, 2, get_class($event));
        $this->assertTrue($trait->hasEvents());


        return $trait;
    }

    /**
     * @depends testAddTwoEvent
     */
    public function testClearEvent(EventLogsReadInterface $trait): void
    {
        $trait->clear();
        $this->assertEventLogs($trait, 0);
        $this->assertFalse($trait->hasEvents());
    }

    public function assertEventLogs(EventLogsReadInterface $trait, int $count = 0, string $className = '')
    {
        $this->assertSame($count, $trait->countEvents());
        $this->assertIsArray($trait->getEvents());
        $this->assertSame($count, count($trait->getEvents()));
        $this->assertSame($count, $trait->countEvents());
        if ($trait->countEvents() > 0) {
            $shortName = $this->getShortNameClass($className);

            /**
             * @todo przemodelować tak by był zwracany typ/ podpowiedzi 
             */
            $this->assertSame($shortName, $trait->getEvents()[0]->eventName);
            $this->assertInstanceOf(EmailDetailsNotification::class, $trait->getEvents()[0]->details);
            $this->assertSame('to', $trait->getEvents()[0]->details->to);
        }
    }

    private function getShortNameClass($object)
    {
        return (new \ReflectionClass($object))->getShortName();
    }
}
