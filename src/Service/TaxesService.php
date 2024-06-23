<?php

declare(strict_types=1);

namespace App\Service;

use App\Constant\TaxesControllerConstant;
use App\Dto\TaxesInputDto;
use App\Dto\TaxesProcessDto;
use App\Dto\TaxesResultDto;
use App\Exception\InvalidAdapterException;
use App\Exception\ValidationException;
use App\Validator\TaxesInputValidator;

readonly class TaxesService
{
    /**
     * @param TaxesInputValidator $validator
     * @param TaxBeeAdapterService $taxBeeAdapterService
     * @param SeriousTaxAdapterService $seriousTaxAdapterService
     */
    public function __construct(
        private TaxesInputValidator      $validator,
        private TaxBeeAdapterService     $taxBeeAdapterService,
        private SeriousTaxAdapterService $seriousTaxAdapterService,
    ) {
    }

    /**
     * @param TaxesInputDto $inputDto
     * @return array<int, TaxesResultDto>
     * @throws ValidationException
     * @throws InvalidAdapterException
     */
    public function getTaxes(TaxesInputDto $inputDto): array
    {
        $errors = $this->validator->validate($inputDto);

        if (0 !== count($errors)) {
            throw new ValidationException(TaxesControllerConstant::VALIDATION_ERROR_MESSAGE->value);
        }

        $dto = new TaxesProcessDto($inputDto->country, $inputDto->state);

        $manager = new TaxesManagerService([
            $this->taxBeeAdapterService,
            $this->seriousTaxAdapterService,
        ]);

        return $manager->execute($dto);
    }
}
