<?php

namespace App\US\Application\Write\Command\CreateUser;

use App\US\Domain\Email\Email;
use App\US\Domain\User\Event\UserCreated;
use App\US\Domain\User\User;
use App\US\Domain\User\UserID;
use App\US\Domain\User\UserRepositoryInterface;
use App\US\Infrastructure\Persistent\Doctrine\Repository\UserRepository2;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly MessageBusInterface $eventBus,
        private readonly ValidatorInterface $validator
    ) {
    }

    public function __invoke(CreateUserCommand $createUserCommand)
    {

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
        $this->userRepository->save($user);

        /**
         * @todo Przeerobć na ES i asychroniczniee
         */
        $userCreeatedEvent = new UserCreated($createUserCommand->uuid);
        $this->eventBus->dispatch(
            (new Envelope($userCreeatedEvent))
                ->with(new DispatchAfterCurrentBusStamp())
        );
    }
}
