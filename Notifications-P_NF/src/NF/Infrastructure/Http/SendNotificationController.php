<?php

namespace App\NF\Infrastructure\Http;

use App\NF\Infrastructure\Event\SendNotificationEvent;
use App\NF\Infrastructure\Response\NotificationResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SendNotificationController extends AbstractController
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    #[Route('/notification', methods: ['POST'])]
    public function notification(): JsonResponse
    {
        $this->eventDispatcher->dispatch(
            new SendNotificationEvent(),
            SendNotificationEvent::NAME
        );

        return new NotificationResponse();
    }
}
