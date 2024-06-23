<?php

declare(strict_types=1);

namespace App\Adapter;

use App\Constant\TaxesTypeResultConstant;
use App\Dto\TaxesProcessDto;
use App\Dto\TaxesResultDto;
use App\ExternalService\SeriousTax\Location;
use App\ExternalService\SeriousTax\SeriousTaxService;
use App\ExternalService\SeriousTax\TimeoutException;

readonly class SeriousTaxAdapter implements TaxesAdapterInterface
{
    /**
     * @param SeriousTaxService $seriousTax
     */
    public function __construct(
        private SeriousTaxService $seriousTax
    ) {
    }

    /**
     * @throws TimeoutException
     * @inheritDoc
     */
    public function execute(TaxesProcessDto $dto): array
    {
        $result = $this->seriousTax->getTaxesResult($this->prepareData($dto));

        return $this->mapResult($result);
    }

    /**
     * @param TaxesProcessDto $dto
     * @return Location
     */
    private function prepareData(TaxesProcessDto $dto): Location
    {
        return new Location($dto->country, $dto->state);
    }

    /**
     * @return array<int, TaxesResultDto>
     */
    private function mapResult(float $result): array
    {
        return [
            new TaxesResultDto(
                TaxesTypeResultConstant::VAT->value,
                $result,
            )
        ];
    }
}
