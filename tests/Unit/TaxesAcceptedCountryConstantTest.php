<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Constant\TaxesAcceptedCountryConstant;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaxesAcceptedCountryConstantTest extends WebTestCase
{
    public function testToArrayMethod(): void
    {
        $this->assertEquals(
            ['US', 'CA'],
            TaxesAcceptedCountryConstant::toArray(TaxesAcceptedCountryConstant::getTaxBeeCountries())
        );
    }

    public function testGetAllCountries(): void
    {
        $this->assertEquals(
            ['US', 'CA', 'LT', 'LV', 'EE', 'PL', 'DE'],
            TaxesAcceptedCountryConstant::toArray(TaxesAcceptedCountryConstant::getAllCountries())
        );
    }

    public function testGetSeriousTaxCountries(): void
    {
        $this->assertEquals(
            ['LT', 'LV', 'EE', 'PL', 'DE'],
            TaxesAcceptedCountryConstant::toArray(TaxesAcceptedCountryConstant::getSeriousTaxCountries())
        );
    }

    public function testIsTaxBeeCountry(): void
    {
        $this->assertTrue(TaxesAcceptedCountryConstant::isTaxBeeCountry('US'));
        $this->assertTrue(TaxesAcceptedCountryConstant::isTaxBeeCountry('CA'));
        $this->assertFalse(TaxesAcceptedCountryConstant::isTaxBeeCountry('LT'));
        $this->assertFalse(TaxesAcceptedCountryConstant::isTaxBeeCountry('LV'));
        $this->assertFalse(TaxesAcceptedCountryConstant::isTaxBeeCountry('EE'));
        $this->assertFalse(TaxesAcceptedCountryConstant::isTaxBeeCountry('PL'));
        $this->assertFalse(TaxesAcceptedCountryConstant::isTaxBeeCountry('DE'));
        $this->assertFalse(TaxesAcceptedCountryConstant::isTaxBeeCountry('invalid'));
    }

    public function testIsSeriousTaxCountry(): void
    {
        $this->assertFalse(TaxesAcceptedCountryConstant::isSeriousTaxCountry('US'));
        $this->assertFalse(TaxesAcceptedCountryConstant::isSeriousTaxCountry('CA'));
        $this->assertTrue(TaxesAcceptedCountryConstant::isSeriousTaxCountry('LT'));
        $this->assertTrue(TaxesAcceptedCountryConstant::isSeriousTaxCountry('LV'));
        $this->assertTrue(TaxesAcceptedCountryConstant::isSeriousTaxCountry('EE'));
        $this->assertTrue(TaxesAcceptedCountryConstant::isSeriousTaxCountry('PL'));
        $this->assertTrue(TaxesAcceptedCountryConstant::isSeriousTaxCountry('DE'));
        $this->assertFalse(TaxesAcceptedCountryConstant::isSeriousTaxCountry('invalid'));
    }
}
