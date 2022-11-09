<?php

namespace App\NF\Infrastructure\Repository;

use App\NF\Infrastructure\Event\EventStream;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Uid\Uuid;

class InMemoryEventStreamReppository implements EventStreamRepositoryInterface
{
    /**
     * @var array[
     * 'string' => 'EventStream'  
     * ]
     *
     */
    private array $eventStream = [];

    public function create(Uuid $uuid): EventStream
    {
        return new EventStream($uuid, 0, new DateTimeImmutable('00:00 00.00.0000'));
    }

    public function exist(Uuid $uuid): bool
    {
        return array_key_exists((string) $uuid, $this->eventStream);
    }

    public function get(Uuid $uuid): EventStream
    {
        if ($this->exist($uuid)) {
            return $this->eventStream[$uuid];
        }
        /**
         * @todo dodać konkreetny wyjątek
         */
        throw new Exception("No exist stream");
    }
}
