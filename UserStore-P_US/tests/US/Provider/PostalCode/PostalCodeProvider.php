<?php

namespace App\Tests\US\Provider\PostalCode;

use App\US\Domain\Address\Country;
use App\US\Domain\PostalCode\PostalCode;
use Faker\Factory;
use Faker\Provider\pl_PL\Address;

class PostalCodeProvider implements PostalCodeProviderInterface
{
    public const DEFAULT_POSTCODE = '59-300';
    public const DEFAULT_COUNTRY = 'PL';

    public static function defaults(): PostalCode
    {
        return new PostalCode(self::DEFAULT_POSTCODE, Country::from(self::DEFAULT_COUNTRY));
    }

    public static function create(): PostalCode
    {
        return self::defaults();
    }

    public static function random(): PostalCode
    {
        $faker = Factory::create();
        $postcode_pl = Address::postcode();

        return new PostalCode($postcode_pl, Country::from($faker->randomElement(['PL'])));
    }

    public static function with(object|array|string|int|null ...$value): PostalCode
    {
        return new PostalCode($value[0], $value[1]);
    }

    public static function withPostCode(string $postCode, Country $country): PostalCode
    {
        return self::with($postCode, $country);
    }
}
