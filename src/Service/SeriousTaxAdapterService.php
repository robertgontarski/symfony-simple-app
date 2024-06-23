<?php

declare(strict_types=1);

namespace App\Service;

use App\Adapter\SeriousTaxAdapter;
use App\Constant\TaxesAcceptedCountryConstant;

readonly class SeriousTaxAdapterService extends AbstractTaxesAdapterService
{
    /**
     * @param SeriousTaxAdapter $adapter
     */
    public function __construct(SeriousTaxAdapter $adapter)
    {
        parent::__construct($adapter);
    }

    /**
     * @param string $county
     * @return self|null
     */
    public function getAdapterByCountry(string $county): ?self
    {
        if (false === TaxesAcceptedCountryConstant::isSeriousTaxCountry($county)) {
            return null;
        }

        return $this;
    }
}
