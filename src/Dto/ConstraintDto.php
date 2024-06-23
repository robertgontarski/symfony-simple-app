<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraint;

readonly class ConstraintDto implements DtoInterface
{
    /**
     * @param mixed $value
     * @param array<int, Constraint> $constraints
     */
    public function __construct(
        public mixed $value,
        public array $constraints,
    ) {
    }

    /**
     * @return array<string, string|array<int, Constraint>>
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'constraints' => $this->constraints,
        ];
    }
}
