<?php

declare(strict_types=1);

namespace App\Constant;

enum TaxesValidatorConstant: string
{
    case DTO_INSTANCE_ERROR_MESSAGE = 'unexpected DTO instance';
    case BLANK_ERROR_MESSAGE = 'is required';
    case NOT_SUPPORTED_ERROR_MESSAGE = 'is not supported';
}
