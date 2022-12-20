<?php

declare(strict_types=1);

namespace App\NF\Infrastructure\Http;

use App\NF\Infrastructure\Factory\NotificationFactory;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SendNotificationController extends AbstractController
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private LoggerInterface $logger, // @phpstan-ignore-line
        private NotificationFactory $notificationFactory
    ) {
    }

    #[Route('/notification', methods: ['POST'])]
    public function notification(Request $request): JsonResponse
    {
        $this->eventDispatcher->dispatch(
            $this->notificationFactory->command($request),
            $this->notificationFactory->getNameCommand()
        );

        return $this->notificationFactory->response();
    }
}
