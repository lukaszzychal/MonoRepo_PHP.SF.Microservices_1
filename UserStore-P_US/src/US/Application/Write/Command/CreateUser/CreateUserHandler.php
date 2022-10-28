<?php

declare(strict_types=1);

namespace App\US\Application\Write\Command\CreateUser;

use App\US\Domain\Email\Email;
use App\US\Domain\User\Event\UserCreatedEvent;
use App\US\Domain\User\User;
use App\US\Domain\User\UserID;
use App\US\Domain\User\UserWriteRepositoryInterface;
use Exception;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateUserHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly UserWriteRepositoryInterface $userWriteRepository,
        private readonly MessageBusInterface $eventBus,
        private readonly ValidatorInterface $validator
    ) {
    }

    public function __invoke(CreateUserCommand $createUserCommand): void
    {
        try {
            $user = User::create(
                new UserID(Uuid::fromString($createUserCommand->uuid)),
                $createUserCommand->firstName,
                $createUserCommand->lastName,
                new Email($createUserCommand->email),
                null,
                null,
                ''
            );
            $this->validator->validate($user);
            $this->userWriteRepository->save($user);

            /**
             * @todo Przeerobć na asychroniczniee
             */
            $userCreeatedEvent = new UserCreatedEvent($createUserCommand->uuid);
            $this->eventBus->dispatch(
                (new Envelope($userCreeatedEvent))
                    ->with(new DispatchAfterCurrentBusStamp())
            );
        } catch (Exception $e) {
            dump(__METHOD__);
            dump($e);
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
