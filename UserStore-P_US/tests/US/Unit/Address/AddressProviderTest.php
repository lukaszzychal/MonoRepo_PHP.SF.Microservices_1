<?php

declare(strict_types=1);

namespace Appp\Tests\US\Unit\Email;

use App\Tests\US\Provider\Address\AddressProvider;
use App\US\Domain\Address\Address;
use App\US\Domain\Address\Country;
use App\US\Domain\PostalCode\PostalCode;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @group Unit
 */
class AddressProviderTest extends TestCase
{
    public function testCreate(): void
    {
        $validAddress = new Address(
            Uuid::fromString(AddressProvider::DEFAULT_UUID),
            AddressProvider::DEFAULT_STREET,
            AddressProvider::DEFAULT_HOUSE_NUMBER,
            AddressProvider::DEFAULT_APARTMEENT_NUMBER,
            AddressProvider::DEFAULT_CITY,
            new PostalCode(AddressProvider::DEFAULT_POSTALCODE, AddressProvider::DEFAULT_COUNTRY),
            AddressProvider::DEFAULT_COUNTRY
        );

        $address = AddressProvider::create();

        $this->assertInstanceOf(Address::class, $address);
        $this->assertSameAddress($address, $validAddress);
    }

    public function testRandom(): void
    {
        $address1 = AddressProvider::create();
        $address2 = AddressProvider::random();

        $this->assertNotSameAddress($address1, $address2);
    }

    public function testWithAddress(): void
    {
        $uuid = Uuid::v4();
        $street = 'Uliica';
        $houseNumber = '4b';
        $apartmentNumber = null;
        $city = 'miasto';
        $postalCode = '59-300';
        $country = 'PL';

        $address = AddressProvider::withAddress(
            $uuid,
            $street,
            $houseNumber,
            $apartmentNumber,
            $city,
            new PostalCode($postalCode, Country::from($country)),
            Country::from($country)
        );

        $this->assertSame($street, $address->street);
        $this->assertSame($houseNumber, $address->houseNumber);
        $this->assertSame($apartmentNumber, $address->apartmentNumber);
        $this->assertSame($postalCode, $address->postalCode->value);
        $this->assertSame($city, $address->city);
        $this->assertSame($country, $address->country->value);
    }

    private function assertSameAddress(Address $address1, Address $address2)
    {
        $this->assertInstanceOf(Address::class, $address1);
        $this->assertInstanceOf(Address::class, $address2);
        $this->assertSame(get_class($address1), get_class($address2));
        $this->assertSame($address1->street, $address2->street);
        $this->assertSame($address1->houseNumber, $address2->houseNumber);
        $this->assertSame($address1->apartmentNumber, $address2->apartmentNumber);
        $this->assertSame((string) $address1->postalCode, (string) $address2->postalCode);
        $this->assertSame($address1->city, $address2->city);
        $this->assertSame($address1->country, $address2->country);
    }

    private function assertNotSameAddress(Address $address1, Address $address2)
    {
        $this->assertInstanceOf(Address::class, $address1);
        $this->assertInstanceOf(Address::class, $address2);

        $this->assertNotSame($address1->street, $address2->street);
        $this->assertNotSame($address1->houseNumber, $address2->houseNumber);
        $this->assertNotSame($address1->apartmentNumber, $address2->apartmentNumber);
        $this->assertNotSame($address1->postalCode, $address2->postalCode);
        $this->assertNotSame($address1->city, $address2->city);
        $this->assertSame($address1->country->value, $address2->country->value);
    }
}
