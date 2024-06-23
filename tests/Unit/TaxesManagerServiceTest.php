<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Adapter\SeriousTaxAdapter;
use App\Adapter\TaxBeeAdapter;
use App\Dto\TaxesProcessDto;
use App\Exception\InvalidAdapterException;
use App\ExternalService\SeriousTax\SeriousTaxService;
use App\ExternalService\TaxBee\TaxBee;
use App\Service\SeriousTaxAdapterService;
use App\Service\TaxBeeAdapterService;
use App\Service\TaxesManagerService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaxesManagerServiceTest extends KernelTestCase
{
    private TaxesManagerService $taxesManagerService;

    protected function setUp(): void
    {
        $this->taxesManagerService = new TaxesManagerService([
            new SeriousTaxAdapterService(new SeriousTaxAdapter(new SeriousTaxService())),
            new TaxBeeAdapterService(new TaxBeeAdapter(new TaxBee())),
        ]);
    }

    /**
     * @throws InvalidAdapterException
     */
    public function testExecuteValidCountryForTaxBee(): void
    {
        $dto = new TaxesProcessDto('US', 'california');
        $results = $this->taxesManagerService->execute($dto);

        $this->assertIsArray($results);
    }

    /**
     * @throws InvalidAdapterException
     */
    public function testExecuteValidCountryForSeriousTax(): void
    {
        $dto = new TaxesProcessDto('LT', 'new york');
        $results = $this->taxesManagerService->execute($dto);

        $this->assertIsArray($results);
    }

    /**
     * @throws InvalidAdapterException
     */
    public function testExecuteInvalidCountry(): void
    {
        $this->expectExceptionMessage('adapter not found');
        $dto = new TaxesProcessDto('invalid', 'london');
        $this->taxesManagerService->execute($dto);
    }
}
