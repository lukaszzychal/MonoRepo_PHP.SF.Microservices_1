<?php

declare(strict_types=1);

namespace App\NF\Application\Write\Handler;

use App\NF\Application\Write\Command\CreateEmailNotifcationCommand;
use App\NF\Application\Write\Command\SendEmailCommand;
use App\NF\Domain\Enum\TypeEnum;
use App\NF\Domain\Model\EmailDetailsNotification;
use App\NF\Domain\Model\Notification;
use App\NF\Domain\Model\NotificationId;
use App\NF\Domain\Repository\NotificationRepositoryInterface;
use Symfony\Component\Messenger\Envelope as MessengerEnvelope;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

final class CreateNotificationEventHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepository,
        private readonly MessageBusInterface $commandBus
    ) {
    }

    public function __invoke(CreateEmailNotifcationCommand $command): void
    {
        $notificationId = NotificationId::random();
        $notification = Notification::create(
            $notificationId,
            TypeEnum::from($command->type),
            new EmailDetailsNotification(
                $command->email,
                'email_from_config@test',
                $command->subject,
                $command->context
            )
        );

        $this->notificationRepository->save($notification);

        $this->commandBus->dispatch(
            (new MessengerEnvelope(
                new SendEmailCommand(
                    $command->type,
                    $command->email,
                    $command->context,
                    $command->subject
                )
            ))->with(new DispatchAfterCurrentBusStamp())
        );
    }
}
