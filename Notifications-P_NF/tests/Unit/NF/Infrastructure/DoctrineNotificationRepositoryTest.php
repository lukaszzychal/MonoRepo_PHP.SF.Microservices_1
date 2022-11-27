<?php

declare(strict_types=1);

namespace App\Tests\Unit\NF\Infrastructure;

use App\Tests\Providers\NotificationProvider;
use App\Tests\TestDouble\FakeDoctrineNotificationRepository;
use PHPUnit\Framework\TestCase;

/**
 * @group  unit
 * @group infrastructure
 * @group udnr
 */
class DoctrineNotificationRepositoryTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testInterfaceSave(): void
    {
        $repo = new FakeDoctrineNotificationRepository();

        $this->assertSame(0, count($repo->notifications));
        $notification = NotificationProvider::createNotificaton();
        $repo->save($notification);
        $this->assertSame(1, count($repo->notifications));
    }
}
