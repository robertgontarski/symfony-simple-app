<?php

declare(strict_types=1);

namespace App\Adapter;

use App\Dto\TaxesProcessDto;
use App\Dto\TaxesResultDto;

interface TaxesAdapterInterface
{
    /**
     * @param TaxesProcessDto $dto
     * @return array<int, TaxesResultDto>
     */
    public function execute(TaxesProcessDto $dto): array;
}
