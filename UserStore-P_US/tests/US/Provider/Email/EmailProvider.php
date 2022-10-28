<?php

declare(strict_types=1);

namespace App\Tests\US\Provider\Email;

use App\US\Domain\Email\Email;
use Faker\Factory;

class EmailProvider implements EmaiProviderInterface
{
    public const DEFAULT_MAIL = 'mail.testowy@test.pl';

    public static function defaults(): Email
    {
        return new Email(self::DEFAULT_MAIL);
    }

    public static function create(): Email
    {
        return self::defaults();
    }

    public static function random(): Email
    {
        $faker = Factory::create();

        return new Email($faker->email());
    }

    public static function with(int|string|object|array|null ...$value): object
    {
        return new Email($value[0]);
    }

    public static function withEmail(string $email): Email
    {
        return self::with($email);
    }
}
