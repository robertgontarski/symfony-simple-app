<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Adapter\TaxBeeAdapter;
use App\Dto\TaxesProcessDto;
use App\ExternalService\TaxBee\TaxBee;
use App\ExternalService\TaxBee\TaxBeeException;
use App\Service\TaxBeeAdapterService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaxBeeAdapterServiceTest extends KernelTestCase
{
    private TaxBeeAdapter $adapter;
    private TaxBeeAdapterService $service;

    public function testGetAdapterByCountryWithValidCountry(): void
    {
        $this->assertInstanceOf(TaxBeeAdapterService::class, $this->service->getAdapterByCountry('US'));
    }

    public function testGetAdapterByCountryWithInvalidCountry(): void
    {
        $this->assertNull($this->service->getAdapterByCountry('PL'));
    }

    public function testExecute(): void
    {
        $results = $this->service->execute(
            new TaxesProcessDto('US', 'california')
        );

        $this->assertIsArray($results);
    }

    public function testExecuteWithInvalidCountry(): void
    {
        $this->expectException(TaxBeeException::class);
        $this->service->execute(
            new TaxesProcessDto('PL', 'california')
        );
    }

    protected function setUp(): void
    {
        $this->adapter = new TaxBeeAdapter(new TaxBee());
        $this->service = new TaxBeeAdapterService($this->adapter);
    }
}
