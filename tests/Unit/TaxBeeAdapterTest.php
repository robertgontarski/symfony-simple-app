<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Adapter\TaxBeeAdapter;
use App\Dto\TaxesProcessDto;
use App\Exception\InvalidTaxesTypeResultException;
use App\ExternalService\TaxBee\TaxBee;
use App\ExternalService\TaxBee\TaxBeeException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaxBeeAdapterTest extends WebTestCase
{
    public TaxBeeAdapter $adapter;

    protected function setUp(): void
    {
        $this->adapter = new TaxBeeAdapter(new TaxBee());
    }

    /**
     * @throws InvalidTaxesTypeResultException
     */
    public function testInvalidCountryException(): void
    {
        $this->expectException(TaxBeeException::class);
        $this->expectExceptionMessage('This country is not supported');

        $this->adapter->execute(new TaxesProcessDto('invalid', ''));
    }

    /**
     * @throws InvalidTaxesTypeResultException
     * @throws TaxBeeException
     */
    public function testValidCountryWithoutState(): void
    {
        $results = $this->adapter->execute(new TaxesProcessDto('US', ''));

        $this->assertCount(1, $results);
        $this->assertEquals('VAT', $results[0]->taxType);
        $this->assertEquals(20.0, $results[0]->percentage);
    }

    /**
     * @throws InvalidTaxesTypeResultException
     * @throws TaxBeeException
     */
    public function testValidCountryWithInvalidState(): void
    {
        $results = $this->adapter->execute(new TaxesProcessDto('US', 'invalid'));

        $this->assertCount(1, $results);
        $this->assertEquals('VAT', $results[0]->taxType);
        $this->assertEquals(20.0, $results[0]->percentage);
    }

    /**
     * @throws InvalidTaxesTypeResultException
     * @throws TaxBeeException
     */
    public function testValidCountryWithValidState(): void
    {
        $results = $this->adapter->execute(new TaxesProcessDto('US', 'california'));

        $this->assertCount(1, $results);
        $this->assertEquals('VAT', $results[0]->taxType);
        $this->assertEquals(7.25, $results[0]->percentage);
    }

    /**
     * @throws InvalidTaxesTypeResultException
     * @throws TaxBeeException
     */
    public function testValidCountryWithValidStateCaseInsensitive(): void
    {
        $results = $this->adapter->execute(new TaxesProcessDto('US', 'CALIFORNIA'));

        $this->assertCount(1, $results);
        $this->assertEquals('VAT', $results[0]->taxType);
        $this->assertEquals(7.25, $results[0]->percentage);
    }
}
