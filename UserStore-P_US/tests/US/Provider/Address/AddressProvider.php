<?php

namespace App\Tests\US\Provider\Address;

use App\US\Domain\Address\Address;
use App\US\Domain\Address\Country;
use App\US\Domain\PostalCode\PostalCode;
use Faker\Factory;

class AddressProvider implements AddressProviderInterface
{

    public const DEFAULT_STREET = 'street';
    public const DEFAULT_HOUSE_NUMBER = 'house number 4B';
    public const DEFAULT_APARTMEENT_NUMBER = 'apartment numbeer 6b or null';
    public const DEFAULT_CITY = 'city';
    public const DEFAULT_POSTALCODE = '59-300';
    public const DEFAULT_COUNTRY = Country::POLAND;


    public static function defaults(): Address
    {
        return new Address(
            self::DEFAULT_STREET,
            self::DEFAULT_HOUSE_NUMBER,
            self::DEFAULT_APARTMEENT_NUMBER,
            self::DEFAULT_CITY,
            new PostalCode(self::DEFAULT_POSTALCODE, self::DEFAULT_COUNTRY),
            self::DEFAULT_COUNTRY
        );
    }

    public static function create(): Address
    {
        return self::defaults();
    }

    public static function random(): Address
    {
        $faker = Factory::create('pl_PL');

        return new Address(
            $faker->streetName(),
            $faker->buildingNumber(),
            $faker->numberBetween(1, 10),
            $faker->city(),
            new PostalCode($faker->postcode(), Country::from('PL')),
            Country::from('PL')
        );
    }

    public static function with(int|string|object|array|null ...$value): Address
    {
        return new Address(
            $value[0],
            $value[1],
            $value[2],
            $value[3],
            $value[4],
            $value[5]
        );
    }

    public static function withAddress(
        string $street,
        string $houseNumber,
        ?string $apartmentNumber = '',
        string $city,
        PostalCode $postalCode,
        Country $country
    ): Address {
        return self::with($street, $houseNumber, $apartmentNumber, $city, $postalCode, $country);
    }
}
