<?php

namespace App\US\Application\Event;

use App\US\Application\Write\Services\SensitiveDataService;
use App\US\Domain\User\Event\EventHandleInterface;
use App\US\Domain\User\Event\UserCreatedEvent;
use App\US\Domain\User\UserID;
use App\US\Domain\User\UserRepositoryInterface;
use App\US\Infrastructure\Client\Notification\NotificationClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UserCreatedEventHandler implements EventHandleInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SensitiveDataService $sensitiveDataService,
        private readonly NotificationClient $notificationClient,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(UserCreatedEvent $userCreated): void
    {
        $user = $this->userRepository->findUser(UserID::fromString($userCreated->uuid));
        if (!$user) {
            throw new NotFoundHttpException('Not Found User');
        }
        $this->logger->info(
            $this->sensitiveDataService->clear(
                sprintf('Created user: #'.$user->getUuid()->uuid)
            )
        );

        $response = $this->notificationClient->sendEmail(
            $user->getEmail(),
            'Powiadomienie: Utworzono konto użytownika',
            'Utworzono konto użytkownika: '.$user->getFirstName().' '.$user->getLastName()
        );
    }
}
