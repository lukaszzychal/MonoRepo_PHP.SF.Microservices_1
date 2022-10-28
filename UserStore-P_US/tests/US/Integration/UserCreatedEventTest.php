<?php

declare(strict_types=1);

namespace App\Tests\US\Integration;

use App\Tests\US\Provider\User\UserProvider;
use App\US\Application\Event\NotificationTypeEnum;
use App\US\Application\Event\UserCreatedEventHandler;
use App\US\Application\Write\Services\SensitiveDataService;
use App\US\Domain\User\Event\UserCreatedEvent;
use App\US\Domain\User\User;
use App\US\Domain\User\UserReadRepositoryInterface;
use App\US\Domain\User\UserWriteRepositoryInterface;
use App\US\Infrastructure\Client\Notification\NotificationClient;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group integration
 * @group iuc
 */
class UserCreatedEventTest extends KernelTestCase
{
    private User $user;
    private LoggerInterface $logger;
    private SensitiveDataService $sensitivedata;
    private NotificationClient|MockObject  $client;
    private UserWriteRepositoryInterface $userWriteRepo;
    private UserReadRepositoryInterface $userReadRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = UserProvider::create();

        $this->logger = $this->getContainer()->get(LoggerInterface::class);
        $this->sensitivedata = $this->getContainer()->get(SensitiveDataService::class);

        $this->client = $this->createMock(NotificationClient::class);
        /*
         * @var UserWriteRepositoryInterface
         */
        $this->userWriteRepo = $this->getContainer()->get(UserWriteRepositoryInterface::class);
        $this->userWriteRepo->save($this->user);

        $this->userReadRepo = $this->getContainer()->get(UserReadRepositoryInterface::class);
    }

    public function testInvalidCreatedEvent(): void
    {
        $this->expectException(Exception::class);
        $handleer = new UserCreatedEventHandler(
            $this->logger,
            $this->sensitivedata,
            $this->client,
            $this->userReadRepo
        );
        $token = 'CorrectAcceesTokenNotificationService';
        $type = NotificationTypeEnum::EMAIL;
        $dataArray = [];
        $commandEvent = new UserCreatedEvent(
            '123',
            $token,
            $type->value,
            $dataArray
        );
        $handleer($commandEvent);
    }

    public function testCreatedEvent(): void
    {
        /*
         * @var NotificationClient|MockObject
         */
        $this->client
            ->expects($this->once())
            ->method('sendEmail');

        $handleer = new UserCreatedEventHandler(
            $this->logger,
            $this->sensitivedata,
            $this->client,
            $this->userReadRepo
        );

        $token = 'CorrectAcceesTokenNotificationService';
        $type = NotificationTypeEnum::EMAIL;
        $dataArray = [
            'email' => $this->user->getEmail(),
            'context' => 'Hi. You accont was created. :) Welcome :)',
            'subject' => 'User was creeated',
        ];
        $commandEvent = new UserCreatedEvent(
            (string) $this->user->getUuid()->uuid,
            $token,
            $type->value,
            $dataArray
        );

        $handleer($commandEvent);
    }
}
