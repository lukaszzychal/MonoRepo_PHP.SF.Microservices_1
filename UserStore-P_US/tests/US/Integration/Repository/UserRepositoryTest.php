<?php

namespace App\Tests\US\Integration\Repository;

use App\Tests\US\Provider\User\UserProvider;
use App\US\Domain\User\User;
use App\US\Infrastructure\Persistent\Doctrine\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @group Integration
 * @group iur
 */
class UserRepositoryTest extends KernelTestCase
{
    /**
     * @group iur_write
     *
     * @return void
     */
    public function testSaveUserRepo()
    {
        /**
         * @var EntityManagerInterface $entityManager
         */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $user = UserProvider::create();

        $reppo = new UserRepository(
            $entityManager
        );

        $reppo->save($user);

        $userFromDB = $this->getUser($entityManager, $user->getUuid()->uuid);

        $this->assertSame((string) $user->getUuid(), (string) $userFromDB->getUuid());
        $this->assertSame((string) $user->getEmail(), (string) $userFromDB->getEmail());
    }

    /**
     * @group iur_read
     *
     * @return void
     */
    public function testFindUserRepo()
    {
        /**
         * @var EntityManagerInterface $em
         */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $user = UserProvider::create();
        $entityManager->persist($user);
        $entityManager->flush();

        $reppo = new UserRepository(
            $entityManager
        );

        $userFromRepo = $reppo->find($user->getUuid());

        $this->assertSame((string) $user->getUuid(), (string) $userFromRepo->getUuid());
        $this->assertSame((string) $user->getEmail(), (string) $userFromRepo->getEmail());
    }

    private function getUser(EntityManagerInterface $entityManager, Uuid $uuid): ?User
    {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('u')->from(User::class, 'u')
            ->where(' u.uuid = :uuid')->setParameter('uuid', (string) $uuid);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
