<?php

declare(strict_types=1);

namespace App\Service;

use App\Adapter\TaxesAdapterInterface;
use App\Dto\TaxesProcessDto;
use App\Dto\TaxesResultDto;

abstract readonly class AbstractTaxesAdapterService implements TaxesAdapterServiceInterface
{
    /**
     * @param TaxesAdapterInterface $adapter
     */
    public function __construct(
        protected TaxesAdapterInterface $adapter,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(TaxesProcessDto $dto): array
    {
        return $this->adapter->execute($dto);
    }
}
