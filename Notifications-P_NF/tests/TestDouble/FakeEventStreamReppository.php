<?php

namespace App\Tests\TestDouble;

use App\NF\Infrastructure\Event\EventStream;
use App\NF\Infrastructure\Repository\EventStreamRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class FakeEventStreamReppository implements EventStreamRepositoryInterface
{
    private static array $eventStreams = [];

    public function create(Uuid $uuid): EventStream
    {
        self::$eventStreams[] = [];
        self::$eventStreams[(string) $uuid] = EventStream::create($uuid);

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
        throw new \Exception('No exist stream');
    }
}
