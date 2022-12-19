<?php

declare(strict_types=1);

namespace App\NF\Infrastructure\Repository;

use App\NF\Domain\Model\Notification;
use App\NF\Domain\Repository\NotificationRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

class DoctrineNotificationRepository extends ServiceEntityRepository implements NotificationRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly LoggerInterface $logger,
        private readonly EventStoreRepositoryInterface $eventStoreRepository
    ) {
        parent::__construct($registry, Notification::class);
    }

    public function save(Notification $notification): void
    {
        $this->_em->persist($notification);
        $this->_em->flush();

        $this->logger->info('Save data notification #' . $notification->getId());

        $this->eventStoreRepository->storeEvents(
            Uuid::fromString((string) $notification->getId()),
            get_class($notification),
            $notification->getEvents()
        );
    }
}
