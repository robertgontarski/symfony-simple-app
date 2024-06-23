<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\TaxesProcessDto;
use App\Dto\TaxesResultDto;

interface TaxesAdapterServiceInterface
{
    /**
     * @param TaxesProcessDto $dto
     * @return array<int, TaxesResultDto>
     */
    public function execute(TaxesProcessDto $dto): array;

    /**
     * @param string $county
     * @return self|null
     */
    public function getAdapterByCountry(string $county): ?self;
}
