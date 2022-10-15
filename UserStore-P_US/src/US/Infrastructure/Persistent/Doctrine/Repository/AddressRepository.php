<?php

namespace App\US\Infrastructure\Persistent\Doctrine\Repository;

use App\US\Domain\Address\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AddressRepositoryextends extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(Address $entity): void
    {

        $this->_em->persist($entity);
        $this->_em->flush();
    }

    public function remove(Address $entity): void
    {
        $this->_em->remove($entity);
        $this->_em->flush();
    }
}
