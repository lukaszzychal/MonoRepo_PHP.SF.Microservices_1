<?php

namespace App\Integration\NF\Infrastructure;

use App\NF\Infrastructure\Event\EventStream;
use App\NF\Infrastructure\Repository\EventStoreRepositoryInterface;
use App\NF\Infrastructure\Repository\EventStreamRepositoryInterface;
use App\Tests\Providers\NotificationProvider;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @group integration
 * @group infrastructure
 * @group idestr
 * @group doctrine
 */
class DoctrineEventStreamRepositoryTest extends KernelTestCase
{
    use ReloadDatabaseTrait;
    private EventStreamRepositoryInterface $eventStreamRepository;
    private Uuid $uuiid;

    protected function setUp(): void
    {
        /*
         * @var EventStreamRepositoryInterface $eventStreamRepository
         */
        $this->eventStreamRepository = $this->getContainer()->get(EventStreamRepositoryInterface::class);
        $this->uuiid = Uuid::fromString('eca5fcaa-5601-429c-b760-80f6d27821cb');
    }

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
        $this->eventStreamRepository->create($this->uuiid);
        $eventStrem = $this->eventStreamRepository->get($this->uuiid);
        $this->assertSame((string) $this->uuiid, (string) $eventStrem->getId());
        $this->assertSame(0, $eventStrem->getEventNumber());
    }

    public function testEventStreamGetNoExistEvents(): void
    {
        $this->eventStreamRepository->create($this->uuiid);
        $eventStrem = $this->eventStreamRepository->get($this->uuiid);
        $this->assertInstanceOf(EventStream::class, $eventStrem);
        $eventStoreRepository = $this->getContainer()->get(EventStoreRepositoryInterface::class);
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
         * @var EventStoreRepositoryInterface
         */
        $eventStoreRepository = $this->getContainer()->get(EventStoreRepositoryInterface::class);
        $eventStoreRepository->store($eventStrem, __METHOD__, NotificationProvider::createEmailEvent(__METHOD__));
        $this->assertIsArray($eventStrem->getEvents($eventStoreRepository));
        $this->assertSame(1, $eventStrem->getEventNumber());
    }
}
