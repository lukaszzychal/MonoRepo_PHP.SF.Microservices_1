<?php

namespace App\NF\Infrastructure\Repository;

use App\NF\Infrastructure\Event\EventStream;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

//  @phpstan-ignore-next-line
class DoctrineEventStreamRepository extends ServiceEntityRepository implements EventStreamRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct($registry, EventStream::class);
    }

    public function create(Uuid $uuid): EventStream
    {
        $eventStreams = EventStream::create($uuid);

        $this->_em->persist($eventStreams);
        $this->_em->flush();
        $this->logger->info(sprintf('Create Stream. UUID: %s ', $uuid));

        return $eventStreams;
    }

    public function exist(Uuid $uuid): bool
    {
        $qb = $this->createQueryBuilder('stream');
        $qb->select('stream.id');
        $qb->where('stream.id = :uuid')->setParameter('uuid', (string) $uuid);
        $stream = $qb->getQuery()->getOneOrNullResult();

        if (is_null($stream)) {
            return false;
        }

        return true;
    }

    public function get(Uuid $uuid): EventStream
    {
        $qb = $this->createQueryBuilder('stream');
        $qb->where('stream.id = :uuid')->setParameter('uuid', (string) $uuid);
        $stream = $qb->getQuery()->getOneOrNullResult();

        if (is_null($stream)) {
            /*
         * @todo dodać konkreetny wyjątek
         */
            throw new \Exception('No exist stream');
        }

        return $stream;
    }
}
