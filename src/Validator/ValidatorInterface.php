<?php

declare(strict_types=1);

namespace App\Validator;

use App\Dto\DtoInterface;

interface ValidatorInterface
{
    /**
     * @param DtoInterface $dto
     * @return array<int, string>
     */
    public function validate(DtoInterface $dto): array;
}
