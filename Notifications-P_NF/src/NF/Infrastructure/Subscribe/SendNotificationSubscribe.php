<?php

declare(strict_types=1);

namespace App\NF\Infrastructure\Subscribe;

use App\NF\Application\Write\Command\CommandInterface;
use App\NF\Application\Write\Command\TypeNotificationCommand;
use App\NF\Infrastructure\Event\SendNotificationEvent;
use App\NF\Infrastructure\Request\NotificationRequest;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class SendNotificationSubscribe implements EventSubscriberInterface
{
    public function __construct(
        private readonly string $appToken,
        private readonly SerializerInterface $serializer,
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SendNotificationEvent::NAME => [
                'send',
            ],
        ];
    }

    public function send(SendNotificationEvent $sendNotificationEvent): void
    {
        $request = $sendNotificationEvent->request;
        $notifiRequeest = NotificationRequest::fromRequest($request);

        $token = str_replace('Bearer ', '', $notifiRequeest->token);
        if ($this->appToken !== $token) {
            $this->logger->critical("Wrong token [ {$token} ]: File:" . __FILE__ . '  Line: ' . __LINE__);
            // @todo Przerobić na konkretny wyjątek
            throw new \Exception('Wrong token [ ' . $token . ' ]', Response::HTTP_BAD_REQUEST);
        }

        $obj = $this->deserializeRequest($request);
        $this->sendNotification($obj);
    }

    private function sendNotification(CommandInterface $typeeNotification): void
    {
        $this->messageBus->dispatch($typeeNotification);
    }

    private function deserializeRequest(Request $request): CommandInterface
    {
        $obj = $this->serializer->deserialize(
            $request->getContent(),
            TypeNotificationCommand::class,
            'json',
            []
        );

        return $obj;
    }
}
