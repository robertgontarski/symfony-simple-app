<?php

declare(strict_types=1);

namespace App\Decorator;

use App\Cache\TaxesCache;
use App\Dto\TaxesInputDto;
use App\Dto\TaxesResultDto;
use App\Exception\InvalidAdapterException;
use App\Exception\ValidationException;
use App\Service\SeriousTaxAdapterService;
use App\Service\TaxBeeAdapterService;
use App\Service\TaxesService;
use App\Validator\TaxesInputValidator;
use Psr\Cache\InvalidArgumentException;

readonly class TaxesCacheDecorator extends TaxesService
{
    /**
     * @param TaxesInputValidator $validator
     * @param TaxBeeAdapterService $taxBeeAdapterService
     * @param SeriousTaxAdapterService $seriousTaxAdapterService
     * @param TaxesCache $taxesCache
     */
    public function __construct(
        TaxesInputValidator $validator,
        TaxBeeAdapterService $taxBeeAdapterService,
        SeriousTaxAdapterService $seriousTaxAdapterService,
        private TaxesCache $taxesCache,
    ) {
        parent::__construct($validator, $taxBeeAdapterService, $seriousTaxAdapterService);
    }

    /**
     * @param TaxesInputDto $inputDto
     * @return array<int, TaxesResultDto>
     * @throws InvalidAdapterException
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function getTaxes(TaxesInputDto $inputDto): array
    {
        $cacheKey = $this->taxesCache->prepareCacheKey([$inputDto->country, $inputDto->state]);
        $cachedData = $this->taxesCache->getValue($cacheKey);

        if (false === empty($cachedData)) {
            return $cachedData;
        }

        $data = parent::getTaxes($inputDto);
        $this->taxesCache->setValue($cacheKey, $data);

        return $data;
    }
}
