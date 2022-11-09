<?php

namespace App\NF\Infrastructure\Repository;

use App\NF\Infrastructure\Event\EventStream;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Uid\Uuid;

class InMemoryEventStreamReppository implements EventStreamRepositoryInterface
{
    private static array $eventStreams = [];

    public function create(Uuid $uuid): EventStream
    {
        self::$eventStreams[(string) $uuid] = new EventStream($uuid, 0, new DateTimeImmutable('00:00 00.00.0000'));

        return self::$eventStreams[(string) $uuid];
    }

    public function exist(Uuid $uuid): bool
    {
        return array_key_exists((string) $uuid, self::$eventStreams);
    }

    public function get(Uuid $uuid): EventStream
    {
        if ($this->exist($uuid)) {
            return self::$eventStreams[(string) $uuid];
        }
        /*
         * @todo dodać konkreetny wyjątek
         */
        throw new Exception('No exist stream');
    }
}
