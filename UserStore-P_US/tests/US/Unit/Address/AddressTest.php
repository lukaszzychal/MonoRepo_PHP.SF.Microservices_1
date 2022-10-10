<?php

namespace App\Test\US\Unit\User;

use App\US\Domain\Address\Address;
use App\US\Domain\Address\Country;
use App\US\Domain\Address\InvalidAddreessException;
use App\US\Domain\PostalCode\PostalCode;

use PHPUnit\Framework\TestCase;


/**
 * @todo Dodać inne granczne przypadki testowee
 */
class AddressTest extends TestCase
{
    public function testCreateValidAddress(): void
    {
        $streeet = 'street';
        $houseNumber = 'house number 4B';
        $apartmeentNumber = 'apartment numbeer 6b or null';
        $city = 'city';
        $country = Country::POLAND;
        $postalcode = new PostalCode('59-300', $country);

        $addresVO =  new Address(
            $streeet,
            $houseNumber,
            $apartmeentNumber,
            $city,
            $postalcode,
            $country
        );

        $this->assertSame($streeet, $addresVO->street);
        $this->assertSame($houseNumber, $addresVO->houseNumber);
        $this->assertSame($apartmeentNumber, $addresVO->apartmentNumber);
        $this->assertSame($city, $addresVO->city);
        $this->assertSame($postalcode->value, $addresVO->postalCode->value);
        $this->assertSame($postalcode->country->value, $addresVO->postalCode->country->value);
        $this->assertSame($country, $addresVO->country);
    }

    public function testCreateInValidAddress(): void
    {
        $streeet = '';
        $houseNumber = '';
        $apartmeentNumber = 'apartment numbeer 6b or null';
        $city = 'city';
        $country = Country::POLAND;
        $postalcode = new PostalCode('59-300', $country);

        $this->expectException(InvalidAddreessException::class);

        $addresVO =  new Address(
            $streeet,
            $houseNumber,
            $apartmeentNumber,
            $city,
            $postalcode,
            $country
        );
    }
}
