<?php

declare(strict_types=1);

namespace App\Adapter;

use App\Constant\TaxesTypeResultConstant;
use App\Dto\TaxesProcessDto;
use App\Dto\TaxesResultDto;
use App\Exception\InvalidTaxesTypeResultException;
use App\ExternalService\TaxBee\TaxBee;
use App\ExternalService\TaxBee\TaxBeeException;
use App\ExternalService\TaxBee\TaxResult;

readonly class TaxBeeAdapter implements TaxesAdapterInterface
{
    /**
     * @param TaxBee $taxBee
     */
    public function __construct(
        private TaxBee $taxBee,
    ) {
    }

    /**
     * @throws TaxBeeException|InvalidTaxesTypeResultException
     * @inheritDoc
     */
    public function execute(TaxesProcessDto $dto): array
    {
        $results = $this->taxBee->getTaxes(...$this->prepareData($dto));

        return $this->mapResults($results);
    }

    /**
     * @param TaxesProcessDto $dto
     * @return array<string, string>
     */
    private function prepareData(TaxesProcessDto $dto): array
    {
        return [
            'country'  => $dto->country,
            'state'    => $dto->state,
            'city'     => '',
            'street'   => '',
            'postcode' => '',
        ];
    }

    /**
     * @param array<int, TaxResult> $results
     * @return array<int, TaxesResultDto>
     * @throws InvalidTaxesTypeResultException
     */
    private function mapResults(array $results): array
    {
        return array_map(
            fn(TaxResult $result) => new TaxesResultDto(
                TaxesTypeResultConstant::getTypeByValue($result->type->value)->value,
                $result->taxPercentage,
            ),
            $results
        );
    }
}
