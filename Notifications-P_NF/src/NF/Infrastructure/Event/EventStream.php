<?php

namespace App\NF\Infrastructure\Event;

use App\NF\Infrastructure\Repository\EventStoreRepositoryInterface;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;
use Symfony\Component\Uid\Uuid;

use function PHPUnit\Framework\isNan;

class EventStream
{

    private static array $events = [];

    public function __construct(
        private Uuid $id,
        private int $version,
        private ?DateTimeImmutable $updateAt = null,
    ) {
    }

    /**
     * Get the value of version
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    public function IncremetVersion(): void
    {
        $this->version++;
    }

    public function updateDate(DateTimeImmutable $updateAt): void
    {
        $this->updateAt = $updateAt;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    public function getEvents(EventStoreRepositoryInterface $eventStoreRepository): array
    {
        if (is_null(self::$events)) {
            self::$events = $eventStoreRepository->getEvents($this->getId());
        }
        return self::$events;
    }

    public static function createEmptyStream(Uuid $uuid): EventStream
    {
        return new self($uuid, 0, null);
    }
}
