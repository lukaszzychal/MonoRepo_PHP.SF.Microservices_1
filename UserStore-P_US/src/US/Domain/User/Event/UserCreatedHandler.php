<?php

namespace App\US\Domain\User\Event;

use App\US\Application\Write\Services\CleanSensitiveData;
use App\US\Infrastructure\Client\Notification\NotificationClient;
use Psr\Log\LoggerInterface;


class UserCreatedHandler implements EventHandleInterface
{
    public function __construct(
        // private readonly LoggerInterface $logger,
        // private readonly CleanSensitiveData $cleanSensitiveData,
        // private readonly NotificationClient $notificationClient
    )
    {
    }
    public function __invoke(UserCreated $userCreated)
    {
        // $this->logger->info(
        //     $this->cleanSensitiveData->clear(
        //         sprintf("Created user: #{$userCreated->uuid}")
        //     )
        // );


        // $tthis->seendNotification();
    }
}
