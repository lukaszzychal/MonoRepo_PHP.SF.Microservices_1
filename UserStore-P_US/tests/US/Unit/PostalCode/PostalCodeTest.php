<?php

declare(strict_types=1);

namespace App\Test\US\Unit\PostalCode;

use App\US\Domain\Address\Country;
use App\US\Domain\PostalCode\InvalidFormatPostalCodeException;
use App\US\Domain\PostalCode\InvalidPostalCodeException;
use App\US\Domain\PostalCode\PostalCode;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 *
 * @todo Dodać inne granczne przypadki testowee
 */
class PostalCodeTest extends TestCase
{
    public function testCreateWithValidPolandPostalCode()
    {
        $validPostalCode = '59-300';
        $postalCode = new PostalCode($validPostalCode, Country::POLAND);

        $this->assertSame($validPostalCode, $postalCode->value);
        $this->assertSame(Country::POLAND, $postalCode->country);
    }

    public function testCreateWithInValidPolandPostalCode()
    {
        $postalCode = '59-3000b';
        $this->expectException(InvalidFormatPostalCodeException::class);

        new PostalCode($postalCode, Country::POLAND);
    }

    public function testCreateWithEmptyPostalCode()
    {
        $postalCode = '';
        $this->expectException(InvalidPostalCodeException::class);
        $this->expectExceptionMessage(sprintf('Invalid Postal Code %s', $postalCode));
        new PostalCode($postalCode, Country::POLAND);
    }
}
