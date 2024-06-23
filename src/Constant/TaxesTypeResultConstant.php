<?php

declare(strict_types=1);

namespace App\Constant;

use App\Exception\InvalidTaxesTypeResultException;

enum TaxesTypeResultConstant: string
{
    case VAT = 'VAT';
    case GST_HST = 'GST/HST';
    case PST = 'PST';

    /**
     * @param string $value
     * @return self
     * @throws InvalidTaxesTypeResultException
     */
    public static function getTypeByValue(string $value): self
    {
        return match ($value) {
            'VAT' => self::VAT,
            'GST/HST' => self::GST_HST,
            'PST' => self::PST,
            default => throw new InvalidTaxesTypeResultException('invalid tax type'),
        };
    }
}
