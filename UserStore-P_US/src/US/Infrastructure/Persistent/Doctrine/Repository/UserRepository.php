<?php

namespace App\US\Infrastructure\Persistent\Doctrine\Repository;

use App\US\Domain\User\User;
use App\US\Domain\User\UserID;
use App\US\Domain\User\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity): void
    {

        $this->_em->persist($entity);
        $this->_em->flush();
    }

    public function remove(User $entity): void
    {
        $this->_em->remove($entity);
        $this->_em->flush();
    }

    public function findUser(UserID $userId): User
    {

        $qb = $this->createQueryBuilder('u')
            ->where('u.uuid = :uuid')
            ->setParameter('uuid', (string) $userId->uuid);

        $query = $qb->getQuery();
        return $query->getOneOrNullResult();
    }
}
