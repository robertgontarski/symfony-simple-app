<?php

declare(strict_types=1);

namespace App\Validator\Components\ExternalService;

use App\Constant\TaxesAcceptedCountryConstant;
use App\Constant\TaxesValidatorConstant;
use App\Dto\ConstraintDto;
use App\Validator\Components\ValidatorComponentInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

abstract readonly class AbstractValidatorComponent implements ValidatorComponentInterface
{
    /**
     * @var string|null
     */
    protected ?string $country;

    /**
     * @param string|null $country
     */
    public function __construct(?string $country)
    {
        $this->country = $country;
    }

    /**
     * @inheritDoc
     */
    public function getDtoConstraints(): array
    {
        return [
            new ConstraintDto($this->country, $this->getCountryConstraint())
        ];
    }

    /**
     * @return array<int, Constraint>
     */
    private function getCountryConstraint(): array
    {
        return [
            new Assert\NotBlank(
                message: sprintf('country %s', TaxesValidatorConstant::BLANK_ERROR_MESSAGE->value),
                allowNull: false
            ),
            new Assert\Choice(
                choices: TaxesAcceptedCountryConstant::toArray(TaxesAcceptedCountryConstant::getAllCountries()),
                message: sprintf('country %s', TaxesValidatorConstant::NOT_SUPPORTED_ERROR_MESSAGE->value),
            ),
        ];
    }
}