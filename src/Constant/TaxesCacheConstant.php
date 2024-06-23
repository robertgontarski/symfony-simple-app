<?php

declare(strict_types=1);

namespace App\Constant;

enum TaxesCacheConstant: string
{
    case KEY_PREFIX = 'taxes';
    case EXPIRATION_TIME = '3600';
    case SAVE_ERROR_MESSAGE = 'error saving cache';

    public static function generateCacheKey(string $key): string
    {
        return md5(sprintf("%s_%s", self::KEY_PREFIX->value, $key));
    }

    public static function getExpirationTime(): int
    {
        return (int)self::EXPIRATION_TIME->value;
    }
}
