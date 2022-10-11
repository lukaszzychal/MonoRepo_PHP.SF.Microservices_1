<?php

namespace App\US\Domain\PostalCode;

use App\US\Domain\Address\Country;
use Exception;

final class PostalCodeFormatValidation
{
    public const FORMAT_KEY = 'format';
    public const PATTERN_KEY = 'pattern';

    /**
     * 
     *
     * @param string $postalCode
     * @param Country $country
     * @return boolean
     * @throws InvalidFormatPostalCodeException
     */
    public static function valid(string $postalCode, Country $country): bool
    {
        if (!preg_match(self::checkPattern($country), $postalCode)) {
            throw new InvalidFormatPostalCodeException($postalCode, self::getValidFormat($country));
        }

        return true;
    }

    private static function hardCodeFormat(): array /* @phpstan-ignore-line */
    {
        return  [
            Country::POLAND->value => [
                /*
                 * @todo Zamienić  na obiekt
                 */
                self::PATTERN_KEY => '/^[0-9]{2}-[0-9]{3}$/',
                self::FORMAT_KEY => 'XX-XXX',
            ],
        ];
    }


    private static function getValidFormat(Country $country): string
    {
        $map = self::hardCodeFormat();

        return $map[$country->value][self::FORMAT_KEY];
    }

    private static function checkPattern(Country $country): string
    {
        $pattern = self::hardCodeFormat();

        if (!array_key_exists($country->value, $pattern)) {
            /*
             * @todo Dodać osobny wyjątek i test
             */
            throw new Exception('Not found Counttry', 1);
        }

        return $pattern[$country->value][self::PATTERN_KEY];
    }
}
