<?php

declare(strict_types=1);

namespace App\NF\Domain\Repository;

use App\NF\Domain\Model\Notification;

interface NotificationRepositoryInterface
{
    public function save(Notification $notification): void;
}
