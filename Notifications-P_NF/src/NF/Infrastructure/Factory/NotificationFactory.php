<?php

declare(strict_types=1);

namespace App\NF\Infrastructure\Factory;

use App\NF\Infrastructure\Event\CreateNotificationEvent;
use App\NF\Infrastructure\Response\NotificationResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NotificationFactory implements FactoryInterface
{
    public function request(Request $request): RequestInterface
    {
        throw new \Exception('Not implemetation method.');
    }

    public function command(Request $request): CommandInterface
    {
        return new CreateNotificationEvent($request);
    }

    public function response(): JsonResponse
    {
        return new NotificationResponse();
    }

    public function getNameCommand(): string
    {
        return CreateNotificationEvent::NAME;
    }
}
