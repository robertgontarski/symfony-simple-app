<?php

declare(strict_types=1);

namespace App\Validator;

use App\Constant\TaxesAcceptedCountryConstant;
use App\Constant\TaxesValidatorConstant;
use App\Dto\DtoInterface;
use App\Dto\TaxesInputDto;
use App\Validator\Components\ExternalService\SeriousTaxValidatorComponent;
use App\Validator\Components\ExternalService\TaxBeeValidatorComponent;
use RuntimeException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

readonly class TaxesInputValidator extends AbstractValidator
{
    /**
     * @inheritDoc
     */
    public function __construct(
        SymfonyValidatorInterface $validator,
    ) {
        parent::__construct($validator);
    }

    /**
     * @param TaxesInputDto $dto
     * @inheritDoc
     */
    public function validate(DtoInterface $dto): array
    {
        if (false === $this->checkDtoInstance($dto)) {
            throw new RuntimeException(TaxesValidatorConstant::DTO_INSTANCE_ERROR_MESSAGE->value);
        }

        $errors = $this->checkComponent(new SeriousTaxValidatorComponent($dto->country));
        if (0 !== count($errors)) {
            return $errors;
        }

        if (false === TaxesAcceptedCountryConstant::isTaxBeeCountry($dto->country)) {
            return [];
        }

        return $this->checkComponent(new TaxBeeValidatorComponent($dto->country, $dto->state));
    }

    /**
     * @inheritDoc
     */
    protected function checkDtoInstance(DtoInterface $dto): bool
    {
        return $dto instanceof TaxesInputDto;
    }
}
