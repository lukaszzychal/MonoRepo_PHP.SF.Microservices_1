<?php

namespace App\Tests\US\Integration\User;

use App\Tests\US\Provider\User\UserProvider;
use App\US\Domain\User\User;
use App\US\Domain\User\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class UserRepositoryTest extends KernelTestCase
{

    /**
     * @group now11
     *
     * @return void
     */
    public function testSaveUser()
    {
        self::bootKernel();

        /**
         * @var UserRepositoryInterface $reppo
         */
        $reppo = $this->getContainer()->get(UserRepositoryInterface::class);
        $user = UserProvider::create();
        $reppo->save($user);

        /**
         * @var User $userFromDB
         */
        $userFromDB = $reppo->findUser($user->getUuid());

        $this->assertInstanceOf(User::class,  $userFromDB);
        $this->assertSame($user->getEmail(),  $userFromDB->getEmail());

        // @todo Dokończyć test

    }
}
