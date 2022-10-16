<?php

namespace App\NF\Infrastructure\Subscribe;

use App\NF\Application\Write\Command\CommandInterface;
use App\NF\Application\Write\Command\TypeNotificationCommand;
use App\NF\Infrastructure\Event\SendNotificationEvent;
use App\NF\Infrastructure\Request\NotificationRequest;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SendNotificationSubscribe implements EventSubscriberInterface
{
    public function __construct(
        private readonly string $appToken,
        private readonly SerializerInterface $serializer,
        private readonly MessageBusInterface $messageBus,
        private readonly RequestStack $requestStack
    ) {
    }
    public static function getSubscribedEvents(): array
    {
        return  [
            SendNotificationEvent::NAME =>
            [
                'send'
            ]
        ];
    }

    public function send()
    {

        $request = $this->requestStack->getMainRequest();
        $notifiRequeest = NotificationRequest::fromRequest($request);

        if ($this->appToken !== $notifiRequeest->token) {
            throw new \Exception("Wrong token", Response::HTTP_BAD_REQUEST);
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
        $obj =   $this->serializer->deserialize(
            $request->getContent(),
            TypeNotificationCommand::class,
            'json',
            []
        );

        return $obj;
    }
}
