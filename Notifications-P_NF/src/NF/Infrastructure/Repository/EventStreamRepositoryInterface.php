<?php

namespace App\NF\Infrastructure\Repository;

use App\NF\Infrastructure\Event\EventStream;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

interface EventStreamRepositoryInterface
{
    public function create(Uuid $uuid): void;
    public function exist(Uuid $uuid): bool;
    public function get(Uuid $uuid): EventStream;
}
