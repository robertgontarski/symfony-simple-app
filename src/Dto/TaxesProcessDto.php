<?php

declare(strict_types=1);

namespace App\Dto;

readonly class TaxesProcessDto implements DtoInterface
{
    /**
     * @param string $country
     * @param string|null $state
     */
    public function __construct(
        public string $country,
        public ?string $state,
    ) {
    }

    /**
     * @return array<string, null|string>
     */
    public function toArray(): array
    {
        return [
            'country' => $this->country,
            'state' => $this->state,
        ];
    }
}
