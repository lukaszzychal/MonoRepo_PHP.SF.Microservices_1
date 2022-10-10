<?php

namespace App\Tests\US\Provider\Address;


use App\Tests\US\Provider\ProviderInterface;
use App\US\Domain\Address\Address;
use App\US\Domain\Address\Country;
use App\US\Domain\PostalCode\PostalCode;

interface AddressProviderInterface extends ProviderInterface
{
    public static function withAddress(
        string $street,
        string $houseNumber,
        ?string $apartmentNumber = '',
        string $city,
        PostalCode $postalCode,
        Country $country
    ): Address;
}
