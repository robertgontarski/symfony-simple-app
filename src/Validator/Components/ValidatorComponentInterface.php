<?php

declare(strict_types=1);

namespace App\Validator\Components;

use App\Dto\ConstraintDto;

interface ValidatorComponentInterface
{
    /**
     * @return array<int, ConstraintDto>
     */
    public function getDtoConstraints(): array;
}
