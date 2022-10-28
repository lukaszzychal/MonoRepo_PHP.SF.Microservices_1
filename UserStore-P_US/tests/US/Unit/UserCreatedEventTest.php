<?php

declare(strict_types=1);

namespace App\Tests\US\Unit;

use App\Tests\US\Provider\User\UserProvider;
use App\US\Application\Event\UserCreatedEventHandler;
use App\US\Application\Write\Services\SensitiveDataService;
use App\US\Domain\User\Event\UserCreatedEvent;
use App\US\Domain\User\UserReadRepositoryInterface;
use App\US\Infrastructure\Client\Notification\NotificationClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @group unit
 */
class UserCreatedEventTest extends TestCase
{
    public function testCreatedEvent(): void
    {
        $user = UserProvider::create();

        /**
         * @var SensitiveDataService|Stub
         */
        $sensitivedataStub = $this->createStub(SensitiveDataService::class);
        $sensitivedataStub->method('clear')
            ->willReturn('Created user: #' . (string)$user->getUuid()->uuid);

        /**
         * @var LoggerInterface|MockObject
         */
        $loggerMock = $this->createMock(LoggerInterface::class);
        $loggerMock->expects($this->once())->method('info');
        /**
         * @var NotificationClient|MockObject
         */
        $clientMock = $this->createMock(NotificationClient::class);

        $email = $user->getEmail();
        $subject = 'Powiadomienie: Utworzono konto użytownika';
        $context = 'Utworzono konto użytkownika: Lukasz Z';
        $clientMock
            ->expects($this->once())
            ->method('sendEmail')
            ->with($email, $subject, $context);

        /**
         * @var UserReadRepositoryInterface|MockObject
         */
        $userRepo = $this->createMock(UserReadRepositoryInterface::class);
        $userRepo->expects($this->once())
            ->method('find')->willReturn($user);

        /**
         * @var LoggerInterface $loggerMock
         */
        $handleer = new UserCreatedEventHandler(
            $loggerMock,
            $sensitivedataStub,
            $clientMock,
            $userRepo
        );

        $commandEvent = new UserCreatedEvent(
            (string) $user->getUuid()->uuid
        );
        $handleer($commandEvent);
    }
}
