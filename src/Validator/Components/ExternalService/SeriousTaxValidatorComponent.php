<?php

declare(strict_types=1);

namespace App\Validator\Components\ExternalService;

readonly class SeriousTaxValidatorComponent extends AbstractValidatorComponent
{
    /**
     * @param string|null $country
     */
    public function __construct(?string $country)
    {
        parent::__construct($country);
    }
}