<?php

declare(strict_types=1);

namespace App\Integration\NF\Infrastructure;

use App\NF\Domain\Model\Notification;
use App\NF\Domain\Repository\NotificationRepositoryInterface;
use App\Tests\Providers\NotificationProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

/**
 * @group integration
 * @group infrastructure
 * @group idnr
 */
class DoctrineNotificationRepositoryTest extends KernelTestCase
{
    use ReloadDatabaseTrait;

    public function testSaveNotification(): void
    {
        /**
         * @var NotificationRepositoryInterface $repo
         */
        $repo = $this->getContainer()->get(NotificationRepositoryInterface::class);

        $this->assertSame(0, $this->countRow());
        /**
         * @var Notification
         */
        $notification = NotificationProvider::createNotificaton();

        $repo->save($notification);

        $this->assertSame(1, $this->countRow());
    }

    private function countRow(): int
    {
        /**
         * @var EntityManagerInterface
         */
        $em = $this->getContainer()->get(EntityManagerInterface::class);

        $qb = $em->createQueryBuilder();
        $query = $qb->from(Notification::class, 'n')
            ->select('COUNT(n.id)')
            ->getQuery();

        return $query->getSingleScalarResult();
    }
}
