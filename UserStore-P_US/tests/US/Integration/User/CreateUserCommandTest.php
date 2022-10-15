<?php

namespace App\Tests\US\Integration\User;

use App\US\Application\Write\Command\CreateUser\CreateUserCommand;
use App\US\Application\Write\Command\CreateUser\CreateUserHandler as CreateUserCreateUserHandler;
use App\US\Domain\User\UserID;
use App\US\Domain\User\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserCommandTest extends KernelTestCase
{

    /**
     * @group  now2
     */
    public function testCreateUser(): void
    {

        self::bootKernel();

        // /**
        //  * @var UserRepositoryInterface $userRepo
        //  */
        $userRepo = $this->getContainer()->get(UserRepositoryInterface::class);
        $busMock = $this->getContainer()->get(MessageBusInterface::class);
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $command = new CreateUserCommand(
            Uuid::v4(),
            "Łukasz",
            "Z",
            "moj.mail@test.pl"
        );

        $hanler = new CreateUserCreateUserHandler(
            $userRepo,
            $busMock,
            $validator
        );

        $hanler($command);

        $user = $userRepo->findUser(new UserID(Uuid::fromString($command->uuid)));

        $this->assertSame("moj.mail@test.pl", $user->getEmail());
        // ...
    }
}
