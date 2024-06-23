<?php

declare(strict_types=1);

namespace App\Validator\Components\ExternalService;

use App\Constant\TaxesValidatorConstant;
use App\Dto\ConstraintDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

readonly class TaxBeeValidatorComponent extends AbstractValidatorComponent
{
    /**
     * @var string|null
     */
    protected ?string $state;

    /**
     * @param string|null $country
     * @param string|null $state
     */
    public function __construct(?string $country, ?string $state)
    {
        parent::__construct($country);
        $this->state = $state;
    }

    /**
     * @inheritDoc
     */
    public function getDtoConstraints(): array
    {
        return [
            ...parent::getDtoConstraints(),
            new ConstraintDto($this->state, $this->getStateConstraint())
        ];
    }

    /**
     * @return array<int, Constraint>
     */
    private function getStateConstraint(): array
    {
        return [
            new Assert\NotBlank(
                message: sprintf('state %s', TaxesValidatorConstant::BLANK_ERROR_MESSAGE->value),
                allowNull: false
            ),
        ];
    }
}