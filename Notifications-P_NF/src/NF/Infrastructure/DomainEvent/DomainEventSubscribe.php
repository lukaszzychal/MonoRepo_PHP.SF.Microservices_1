<?php

declare(strict_types=1);

namespace App\NF\Infrastructure\DomainEvent;

use App\NF\Domain\Model\Notification;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\NF\Domain\Event\EventLogs\EventLogsTrait;

class DomainEventSubscribe implements EventSubscriberInterface
{
    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Events::postPersist => 'postPersist',
            Events::postRemove => 'postRemove',
            Events::postUpdate => 'postUpdate',
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->store($args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->store($args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->store($args);
    }

    private function store(LifecycleEventArgs $args): void
    {
        /**
         * @var Notification $object
         */
        $object = $args->getObject();
        // @phpstan-ignore-next-line
        if (!in_array(EventLogsTrait::class, class_uses($object), true)) {
            return;
        }

        $eventStore = DomainEventStore::getInstance();

        foreach ($object->getEvents() as $event) {
            $eventStore->addEvent($event);
        }

        $object->clear();
    }
}
