<?php

namespace App\Tests\Unit\NF\Infrastructure;

use App\NF\Infrastructure\Event\EventStream;
use App\NF\Infrastructure\Repository\EventStoreRepositoryInterface;
use App\NF\Infrastructure\Repository\EventStreamRepositoryInterface;
use App\Tests\Providers\NotificationProvider;
use App\Tests\TestDouble\FakeEventStoreRepository;
use App\Tests\TestDouble\FakeEventStreamReppository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @group unit
 * @group infrastructure
 * @group umestr
 * @group InMemory
 */
class EventStreamRepositoryTest extends TestCase
{
    private EventStreamRepositoryInterface $eventStreamRepository;
    private Uuid $uuiid;

    protected function setUp(): void
    {
        /*
         * @var EventStreamRepositoryInterface $eventStreamRepository
         */
        $this->eventStreamRepository = new FakeEventStreamReppository();
        $this->uuiid = Uuid::fromString('eca5fcaa-5601-429c-b760-80f6d27821cb');
    }

    /**
     * @group g111
     */
    public function testEventStreamNoExistStream(): void
    {
        $this->assertFalse($this->eventStreamRepository->exist($this->uuiid));
    }

    public function testEventStreamGetNoExistStream(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No exist stream');
        $this->eventStreamRepository->get($this->uuiid);
    }

    public function testEventStreamCreate(): void
    {
        $this->eventStreamRepository->create($this->uuiid);
        $this->eventStreamRepository->get($this->uuiid);
        $this->assertTrue($this->eventStreamRepository->exist($this->uuiid));
    }

    /**
     * @group g1
     */
    public function testEventStreamGetExistStream(): void
    {
        $eventStrem = $this->eventStreamRepository->get($this->uuiid);
        $this->assertSame((string) $this->uuiid, (string) $eventStrem->getId());
        $this->assertSame(0, $eventStrem->getEventNumber());
    }

    public function testEventStreamGetNoExistEvents(): void
    {
        $eventStrem = $this->eventStreamRepository->get($this->uuiid);
        $this->assertInstanceOf(EventStream::class, $eventStrem);
        $this->assertSame(0, $eventStrem->getEventNumber());
        $eventStoreRepository = new FakeEventStoreRepository($this->eventStreamRepository);
        $this->assertIsArray($eventStrem->getEvents($eventStoreRepository));
        $this->assertSame([], $eventStrem->getEvents($eventStoreRepository));
    }

    /**
     * @group g2
     */
    public function testEventStreamGetExistEvents(): void
    {
        $this->eventStreamRepository->create($this->uuiid);
        $eventStrem = $this->eventStreamRepository->get($this->uuiid);
        $this->assertInstanceOf(EventStream::class, $eventStrem);
        /**
         * @var EventStoreRepositoryInterface $eventStoreRepository
         */
        $eventStoreRepository = new FakeEventStoreRepository($this->eventStreamRepository);
        $eventStoreRepository->store($eventStrem, __METHOD__, NotificationProvider::createEmailEvent(__METHOD__));
        $this->assertIsArray($eventStrem->getEvents($eventStoreRepository));
        $this->assertSame(1, $eventStrem->getEventNumber());
    }
}
