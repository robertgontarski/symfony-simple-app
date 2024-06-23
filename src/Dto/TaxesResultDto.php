<?php

declare(strict_types=1);

namespace App\Dto;

readonly class TaxesResultDto implements DtoInterface
{
    /**
     * @param string $taxType
     * @param float $percentage
     */
    public function __construct(
        public string $taxType,
        public float $percentage,
    ) {
    }

    /**
     * @return array<string, string|float>
     */
    public function toArray(): array
    {
        return [
            'taxType' => $this->taxType,
            'percentage' => $this->percentage,
        ];
    }
}
