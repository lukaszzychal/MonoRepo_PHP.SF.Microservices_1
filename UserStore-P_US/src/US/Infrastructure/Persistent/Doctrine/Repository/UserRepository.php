<?php

namespace App\US\Infrastructure\Persistent\Doctrine\Repository;

use App\US\Domain\User\User;
use App\US\Domain\User\UserID;
use App\US\Domain\User\UserReadRepositoryInterface;
use App\US\Domain\User\UserWriteRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

final class UserRepository implements UserReadRepositoryInterface, UserWriteRepositoryInterface
{
    private string $class;

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        $this->class = User::class;
    }

    public function save(User $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function find(UserID $userId): ?User
    {
        $qb = $this->createQueryBuilder();
        $qb
            ->where('u.uuid = :uuid')
            ->setParameter('uuid', (string) $userId->uuid);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }

    private function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->from($this->class, 'u')->select('u');

        return $qb;
    }
}
