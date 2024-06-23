<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\TaxesProcessDto;
use App\Dto\TaxesResultDto;
use App\Exception\InvalidAdapterException;

readonly class TaxesManagerService
{
    /**
     * @param array<int, TaxesAdapterServiceInterface> $adapters
     */
    public function __construct(
        private array $adapters,
    ) {
    }

    /**
     * @param TaxesProcessDto $dto
     * @return array<int, TaxesResultDto>
     * @throws InvalidAdapterException
     */
    public function execute(TaxesProcessDto $dto): array
    {
        $adapter = $this->getAdapterByCountry($dto->country);
        if (false === $adapter instanceof TaxesAdapterServiceInterface) {
            throw new InvalidAdapterException('adapter not found');
        }

        return $adapter->execute($dto);
    }

    /**
     * @param string $country
     * @return TaxesAdapterServiceInterface|null
     */
    private function getAdapterByCountry(string $country): ?TaxesAdapterServiceInterface
    {
        foreach ($this->adapters as $adapter) {
            $adapter = $adapter->getAdapterByCountry($country);
            if (true === $adapter instanceof TaxesAdapterServiceInterface) {
                return $adapter;
            }
        }

        return null;
    }
}
