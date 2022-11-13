<?php

namespace App\Tests\Integration\NF\Infrastructure;

use App\NF\Domain\Event\EventLogs\EventLogsTrait;
use App\NF\Infrastructure\Event\EventDispatcher;
use App\NF\Infrastructure\Transports\DomainEvent\DomainEventBusInterface;
use App\Tests\Providers\NotificationProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Transport\InMemoryTransport;

/**
 * @group Integration
 * @group Infrastructure
 * @group iedis
 */
class EventDispatcherTest extends KernelTestCase
{
    public function testPublisherEvent(): void
    {

        $trait = new class()
        {
            use EventLogsTrait;
        };

        $trait->addEvent(
            NotificationProvider::createEmailEvent(__METHOD__)
        );
        $trait->addEvent(
            NotificationProvider::createEmailEvent(__METHOD__)
        );

        /**
         * @var DomainEventBusInterface $messageBus
         */
        $messageBus = $this->getContainer()->get(DomainEventBusInterface::class);

        /** 
         * @var InMemoryTransport $transport 
         */
        $transport = $this->getContainer()->get('messenger.transport.domain_event_async');

        $eventDispatcher = new EventDispatcher($messageBus);
        $eventDispatcher->dispatch($trait->getEvents());

        $this->assertCount(2, $transport->get());
    }
}
