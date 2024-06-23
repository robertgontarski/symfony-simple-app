<?php

declare(strict_types=1);

namespace App\Cache;

interface CacheInterface
{
    /**
     * @param string $key
     * @return mixed
     */
    public function getValue(string $key): mixed;

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setValue(string $key, mixed $value): void;
}
