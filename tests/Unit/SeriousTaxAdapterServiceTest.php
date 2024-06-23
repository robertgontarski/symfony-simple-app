<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Adapter\SeriousTaxAdapter;
use App\Dto\TaxesProcessDto;
use App\ExternalService\SeriousTax\SeriousTaxService;
use App\Service\SeriousTaxAdapterService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SeriousTaxAdapterServiceTest extends KernelTestCase
{
    private SeriousTaxAdapter $adapter;
    private SeriousTaxAdapterService $service;

    public function testGetAdapterByCountryWithValidCountry(): void
    {
        $this->assertInstanceOf(SeriousTaxAdapterService::class, $this->service->getAdapterByCountry('PL'));
    }

    public function testGetAdapterByCountryWithInvalidCountry(): void
    {
        $this->assertNull($this->service->getAdapterByCountry('US'));
    }

    public function testExecuteWithInvalidCountry(): void
    {
        $results = $this->service->execute(
            new TaxesProcessDto('US', 'california')
        );

        $this->assertIsArray($results);
    }

    protected function setUp(): void
    {
        $this->adapter = new SeriousTaxAdapter(new SeriousTaxService());
        $this->service = new SeriousTaxAdapterService($this->adapter);
    }
}
