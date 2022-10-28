<?php

namespace App\Tests\US\Integration\User;

use App\Tests\US\Integration\FakeHttpClient;
use App\Tests\US\Integration\FakeHttpResponse;
use App\US\Application\Write\Command\CreateUser\CreateUserCommand;
use App\US\Application\Write\Command\CreateUser\CreateUserHandler;
use App\US\Domain\User\UserID;
use App\US\Domain\User\UserWriteRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @group  Integration
 * @group  icuc
 */
class CreateUserCommandTest extends KernelTestCase
{
    /**
     * @group icuc_write
     */
    public function testCreateUser(): void
    {
        self::bootKernel();

        $this->getContainer()->set(FakeHttpResponse::class, new FakeHttpClient());
        $userRepo = $this->getContainer()->get(UserWriteRepositoryInterface::class);
        $bus = $this->getContainer()->get(MessageBusInterface::class);
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $command = new CreateUserCommand(
            Uuid::v4(),
            'Łukasz',
            'Z',
            'moj.mail@test.pl'
        );

        $hanler = new CreateUserHandler(
            $userRepo,
            $bus,
            $validator
        );

        $hanler($command);

        $user = $userRepo->find(new UserID(Uuid::fromString($command->uuid)));

        $this->assertSame('moj.mail@test.pl', $user->getEmail());
        // ...
    }
}
