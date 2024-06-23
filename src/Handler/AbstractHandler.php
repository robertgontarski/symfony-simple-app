<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Log\LoggerInterface;

abstract readonly class AbstractHandler
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected LoggerInterface $logger,
    ) {
    }
}
