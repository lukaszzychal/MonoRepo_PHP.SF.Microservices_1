<?php

namespace App\Tests\US\Provider\PostalCode;

use App\Tests\US\Provider\ProviderInterface;
use App\US\Domain\Address\Country;
use App\US\Domain\PostalCode\PostalCode;


interface PostalCodeProviderInterface extends ProviderInterface
{
    public static function withPostCode(string $postCode, Country $country): PostalCode;
}
