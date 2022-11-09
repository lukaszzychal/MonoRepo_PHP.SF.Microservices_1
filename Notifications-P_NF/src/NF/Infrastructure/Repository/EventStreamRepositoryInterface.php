<?php

namespace App\NF\Infrastructure\Repository;

use App\NF\Infrastructure\Event\EventStream;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

interface EventStreamRepositoryInterface
{
    public function create(Uuid $uuid): EventStream;
    public function exist(Uuid $uuid): bool;
    public function get(Uuid $uuid): EventStream;
}
