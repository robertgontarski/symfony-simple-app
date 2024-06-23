<?php

declare(strict_types=1);

namespace App\Dto;

readonly class TaxesInputDto implements DtoInterface
{
    /**
     * @param string|null $country
     * @param string|null $state
     */
    public function __construct(
        public ?string $country,
        public ?string $state,
    ) {
    }

    /**
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'country' => $this->country,
            'state' => $this->state,
        ];
    }
}
