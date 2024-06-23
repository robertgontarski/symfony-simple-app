<?php

declare(strict_types=1);

namespace App\Constant;

enum TaxesControllerConstant: string
{
    case VALIDATION_ERROR_MESSAGE = 'validation error';
    case SUCCESS_MESSAGE = 'success';
    case TIMEOUT_ERROR_MESSAGE = 'timeout while try to get taxes';
    case UNEXPECTED_ERROR_MESSAGE = 'unexpected error';
}
