<?php

namespace App\Tests\TestDouble;

use App\NF\Domain\Model\Notification;
use App\NF\Domain\Repository\NotificationRepositoryInterface;

class FakeDoctrineNotificationRepository implements NotificationRepositoryInterface
{
    /**
     * @var Notification[]
     */
    public array $notifications = [];

    public function save(Notification $notification): void
    {
        $this->notifications[(string) $notification->getId()] = $notification;
    }
}
