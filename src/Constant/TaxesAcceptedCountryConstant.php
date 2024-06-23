<?php

declare(strict_types=1);

namespace App\Constant;

use App\Trait\EnumUtilTrait;

enum TaxesAcceptedCountryConstant: string
{
    use EnumUtilTrait;

    case US = 'US';
    case CA = 'CA';
    case LT = 'LT';
    case LV = 'LV';
    case EE = 'EE';
    case PL = 'PL';
    case DE = 'DE';

    /**
     * @return array<int, self>
     */
    public static function getAllCountries(): array
    {
        return [self::US, self::CA, self::LT, self::LV, self::EE, self::PL, self::DE];
    }

    /**
     * @return array<int, self>
     */
    public static function getTaxBeeCountries(): array
    {
        return [self::US, self::CA];
    }

    /**
     * @param string $country
     * @return bool
     */
    public static function isTaxBeeCountry(string $country): bool
    {
        return in_array($country, self::toArray(self::getTaxBeeCountries()), true);
    }

    /**
     * @return array<int, self>
     */
    public static function getSeriousTaxCountries(): array
    {
        return [self::LT, self::LV, self::EE, self::PL, self::DE];
    }

    /**
     * @param string $country
     * @return bool
     */
    public static function isSeriousTaxCountry(string $country): bool
    {
        return in_array($country, self::toArray(self::getSeriousTaxCountries()), true);
    }
}
