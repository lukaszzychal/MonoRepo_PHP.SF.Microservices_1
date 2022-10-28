<?php

declare(strict_types=1);

namespace App\Test\US\Unit\User;

use App\US\Domain\Address\Address;
use App\US\Domain\Address\Country;
use App\US\Domain\Address\InvalidAddreessException;
use App\US\Domain\PostalCode\PostalCode;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @todo Dodać inne granczne przypadki testowee
 *
 * @group Unit
 */
class AddressTest extends TestCase
{
    public function testCreateValidAddress(): void
    {
        $uuid = Uuid::v4();
        $streeet = 'street';
        $houseNumber = 'house number 4B';
        $apartmeentNumber = 'apartment numbeer 6b or null';
        $city = 'city';
        $country = Country::POLAND;
        $postalcode = new PostalCode('59-300', $country);

        $addresVO = new Address(
            $uuid,
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
        $uuid = Uuid::v4();
        $streeet = '';
        $houseNumber = '';
        $apartmeentNumber = 'apartment numbeer 6b or null';
        $city = 'city';
        $country = Country::POLAND;
        $postalcode = new PostalCode('59-300', $country);

        $this->expectException(InvalidAddreessException::class);

        $addresVO = new Address(
            $uuid,
            $streeet,
            $houseNumber,
            $apartmeentNumber,
            $city,
            $postalcode,
            $country
        );
    }
}
