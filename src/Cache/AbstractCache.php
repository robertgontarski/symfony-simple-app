<?php

declare(strict_types=1);

namespace App\Cache;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

abstract readonly class AbstractCache implements CacheInterface
{
    /**
     * @param CacheItemPoolInterface $cache
     */
    public function __construct(
        protected CacheItemPoolInterface $cache,
    ) {
    }

    /**
     * @param array<int, string> $keys
     * @return array<int, CacheItemInterface>
     * @throws InvalidArgumentException
     */
    protected function getCacheElements(array $keys): array
    {
        return array_map(fn(string $key) => $this->getCacheElement($key), $keys);
    }

    /**
     * @param string $key
     * @return CacheItemInterface
     * @throws InvalidArgumentException
     */
    protected function getCacheElement(string $key): CacheItemInterface
    {
        return $this->cache->getItem($key);
    }
}
