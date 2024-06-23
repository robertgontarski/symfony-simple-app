<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Adapter\SeriousTaxAdapter;
use App\Dto\TaxesProcessDto;
use App\ExternalService\SeriousTax\SeriousTaxService;
use App\ExternalService\SeriousTax\TimeoutException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SeriousTaxAdapterTest extends WebTestCase
{
    public SeriousTaxAdapter $adapter;

    protected function setUp(): void
    {
        $this->adapter = new SeriousTaxAdapter(new SeriousTaxService());
    }

    /**
     * @throws TimeoutException
     */
    public function testGetResultsFromInvalidCountry(): void
    {
        $results = $this->adapter->execute(new TaxesProcessDto('invalid', ''));

        $this->assertCount(1, $results);
        $this->assertEquals('VAT', $results[0]->taxType);
        $this->assertEquals(19.0, $results[0]->percentage);
    }

    /**
     * @throws TimeoutException
     */
    public function testGetResultsFromValidCountry(): void
    {
        $results = $this->adapter->execute(new TaxesProcessDto('LT', ''));

        $this->assertCount(1, $results);
        $this->assertEquals('VAT', $results[0]->taxType);
        $this->assertEquals(21.0, $results[0]->percentage);
    }

    public function testThrowExceptionIfTooLong(): void
    {
        $this->expectException(TimeoutException::class);

        $this->adapter->execute(new TaxesProcessDto('PL', ''));
    }
}
