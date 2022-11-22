<?php

namespace App\TestDouble;

use App\NF\Infrastructure\Event\EventStream;
use Symfony\Component\Uid\Uuid;

class StubEventStream
{
    private int $eventNumber = 0;
    public static function create(Uuid $id): EventStream
    {
        return EventStream::create($id);
    }
    public function IncremetVersion(): void
    {
        ++$this->eventNumber;
    }
}
