<?php

namespace App\Tests\US\Provider\User;

use App\Tests\US\Provider\Address\AddressProvider;
use App\Tests\US\Provider\Email\EmailProvider;
use App\US\Domain\Address\Address;
use App\US\Domain\Email\Email;
use App\US\Domain\User\User;
use App\US\Domain\User\UserID;
use DateTimeImmutable;
use Faker\Factory;
use Symfony\Component\Uid\Uuid;

class UserProvider implements UserProviderInterface
{
    public static function defaults(): User
    {
        return User::create(
            new UserID(Uuid::v4()),
            'Lukasz',
            'Z',
            EmailProvider::withEmail('my.email@test.pl'),
            AddressProvider::create(),
            new DateTimeImmutable(),
            ''
        );
    }

    public static function create(): User
    {
        return self::defaults();
    }

    public static function random(): User
    {
        $faker = Factory::create('pl_PL');

        return User::create(
            new UserID(Uuid::fromString($faker->uuid())),
            $faker->firstName(),
            $faker->lastName(),
            EmailProvider::random(),
            AddressProvider::random(),
            new DateTimeImmutable(),
            ''
        );
    }

    public static function with(int|string|object|array|null ...$value): User
    {
        return User::create(
            $value[0],
            $value[1],
            $value[2],
            $value[3],
            $value[4],
            $value[5],
            $value[6]
        );
    }

    public static function withData(
        UserID $uuid,
        string $firstName,
        string $lastName,
        Email $email,
        ?Address $address = null,
        ?DateTimeImmutable $birthDate = null,
        ?string $avatar = ''
    ): User {
        return self::with($uuid, $firstName, $lastName, $email, $address, $birthDate, $avatar);
    }
}
