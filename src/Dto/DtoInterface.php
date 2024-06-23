<?php

declare(strict_types=1);

namespace App\Dto;

interface DtoInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
