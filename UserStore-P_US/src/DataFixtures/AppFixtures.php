<?php

namespace App\DataFixtures;

use App\Tests\US\Provider\User\UserProvider;
use App\US\Domain\Email\Email;
use App\US\Domain\User\UserID;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    public const USER_UUID = 'cf1e5b0d-77f7-4e83-8e99-ab74f715d5cb';
    public const USER_EMAIIL = 'my.mail@test.pl';

    public function load(ObjectManager $manager): void
    {
        $useer = UserProvider::withData(
            new UserID(Uuid::fromString(self::USER_UUID)),
            'Lukasz',
            'Z',
            Email::fromString(self::USER_EMAIIL),
            null,
            new DateTimeImmutable(),
            ''
        );
        $manager->persist($useer);

        for ($i = 0; $i < 10; ++$i) {
            $user = UserProvider::random();
            $manager->persist($user);
        }

        $manager->flush();
    }
}
