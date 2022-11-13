<?php

declare(strict_types=1);

namespace App\Unit\NF\Domain\Unit\Event;

use App\NF\Infrastructure\Event\EventStream;
use App\Tests\Providers\NotificationProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @group  Unit
 * @group Domain
 * @group uest
 */
class EventStreamTest extends TestCase
{
    private const UUID = '50180b33-cc54-47d1-a193-5d26f5ddd982';

    public function testEventStream(): void
    {
        $event = NotificationProvider::createEmailEvent();
        $eventStream = EventStream::createEmptyStream(Uuid::fromString(self::UUID));
        $this->assertSame(0, $eventStream->getVersion());
        $eventStream->IncremetVersion();
        $this->assertSame(1, $eventStream->getVersion());
    }
}
