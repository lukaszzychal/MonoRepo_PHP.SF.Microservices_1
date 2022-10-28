<?php

declare(strict_types=1);

namespace Appp\Tests\US\Unit\Email;

use App\Tests\US\Provider\PostalCode\PostalCodeProvider;
use App\US\Domain\Address\Country;
use App\US\Domain\PostalCode\PostalCode;
use PHPUnit\Framework\TestCase;

/**
 * @group Unit
 */
class PostalCodeProviderTest extends TestCase
{
    public function testCreate(): void
    {
        $validPostalCode = PostalCodeProvider::DEFAULT_POSTCODE;
        $validCountry = PostalCodeProvider::DEFAULT_COUNTRY;

        $postalCode = PostalCodeProvider::create();

        $this->assertInstanceOf(PostalCode::class, $postalCode);
        $this->assertSame($validPostalCode, $postalCode->value);
        $this->assertSame($validCountry, $postalCode->country->value);
    }

    public function testRandom(): void
    {
        $postalCode1 = PostalCodeProvider::random();
        $postalCode2 = PostalCodeProvider::random();

        $this->assertNotSame($postalCode1->value, $postalCode2->value);
        $this->assertSame($postalCode1->country->value, $postalCode2->country->value);
    }

    public function testWith(): void
    {
        $validPostCode = '59-300';
        $validCountry = 'PL';

        $postalCode = PostalCodeProvider::withPostCode($validPostCode, Country::from($validCountry));

        $this->assertSame($validPostCode, $postalCode->value);
        $this->assertSame($validCountry, $postalCode->country->value);
    }
}
