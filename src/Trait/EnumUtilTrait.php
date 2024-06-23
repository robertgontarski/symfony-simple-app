<?php

declare(strict_types=1);

namespace App\Trait;

trait EnumUtilTrait
{
    /**
     * @param array<int, self> $cases
     * @return array<int, string>
     */
    public static function toArray(array $cases): array
    {
        return array_map(fn(self $case) => $case->value, $cases);
    }
}
