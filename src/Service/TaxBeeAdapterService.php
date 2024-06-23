<?php

declare(strict_types=1);

namespace App\Service;

use App\Adapter\TaxBeeAdapter;
use App\Constant\TaxesAcceptedCountryConstant;

readonly class TaxBeeAdapterService extends AbstractTaxesAdapterService
{
    /**
     * @param TaxBeeAdapter $adapter
     */
    public function __construct(TaxBeeAdapter $adapter)
    {
        parent::__construct($adapter);
    }

    /**
     * @param string $county
     * @return self|null
     */
    public function getAdapterByCountry(string $county): ?self
    {
        if (false === TaxesAcceptedCountryConstant::isTaxBeeCountry($county)) {
            return null;
        }

        return $this;
    }
}
