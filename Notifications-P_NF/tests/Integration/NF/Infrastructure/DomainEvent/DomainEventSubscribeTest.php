<?php

namespace App\Tests\Integration\NF\Infrastructure\DomainEvent;

use App\NF\Domain\Enum\TypeEnum;
use App\NF\Domain\Model\Notification;
use App\NF\Domain\Model\NotificationId;
use App\NF\Infrastructure\DomainEvent\DomainEventStore;
use App\NF\Infrastructure\DomainEvent\DomainEventSubscribe;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @group integration
 * @group ides
 */
class DomainEventSubscribeTest extends KernelTestCase
{
    private EventDispatcherInterface $dispatcher;
    private DomainEventSubscribe $subsriber;
    private EntityManager $em;

    protected function setUp(): void
    {
        self::bootKernel();

        /*
         * @var EventDispatcherInterface $dispatcher
         *
         */
        $this->dispatcher = $this->getContainer()->get(EventDispatcherInterface::class);

        $this->em = $this->getContainer()->get(EntityManagerInterface::class);

        $this->subsriber = new DomainEventSubscribe();
        $this->dispatcher->addSubscriber($this->subsriber);

        $this->httpKernel = $this->getContainer()->get(HttpKernelInterface::class);
    }

    public function testPostPersistSubscribe(): void
    {
        $store = DomainEventStore::getInstance();
        $this->assertFalse($store->hasEvents());

        $notification = new Notification(NotificationId::fromUUID(Uuid::v4()), TypeEnum::EMAIL);
        $this->assertSame(1, $notification->countEvents());

        $event = new LifecycleEventArgs($notification, $this->em);
        $this->dispatcher->dispatch($event, Events::postPersist);

        $this->assertSame(0, $notification->countEvents());
        $this->assertSame(1, $store->countEvents());
    }

    public function testPostUpdateSubscribe(): void
    {
        $store = DomainEventStore::getInstance();
        $this->assertSame(1, $store->countEvents());

        $notification = new Notification(NotificationId::fromUUID(Uuid::v4()), TypeEnum::EMAIL);
        $this->assertSame(1, $notification->countEvents());

        $event = new LifecycleEventArgs($notification, $this->em);
        $this->dispatcher->dispatch($event, Events::postUpdate);

        $this->assertSame(0, $notification->countEvents());
        $this->assertSame(2, $store->countEvents());
    }

    public function testPostRemoveSubscribe(): void
    {
        $store = DomainEventStore::getInstance();
        $this->assertSame(2, $store->countEvents());

        $notification = new Notification(NotificationId::fromUUID(Uuid::v4()), TypeEnum::EMAIL);
        $this->assertSame(1, $notification->countEvents());

        $event = new LifecycleEventArgs($notification, $this->em);
        $this->dispatcher->dispatch($event, Events::postRemove);

        $this->assertSame(0, $notification->countEvents());
        $this->assertSame(3, $store->countEvents());
    }
}
