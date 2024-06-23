<?php

declare(strict_types=1);

namespace App\Cache;

use App\Constant\TaxesCacheConstant;
use App\Dto\TaxesResultDto;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;

readonly class TaxesCache extends AbstractCache
{
    private LoggerInterface $logger;

    /**
     * @param CacheItemPoolInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(
        CacheItemPoolInterface $cache,
        LoggerInterface $logger
    ) {
        parent::__construct($cache);
        $this->logger = $logger;
    }

    /**
     * @param string $key
     * @return array<int, TaxesResultDto>|null|mixed
     * @throws InvalidArgumentException
     */
    public function getValue(string $key): mixed
    {
        $element = $this->getCacheElement($key);
        if (false === $element->isHit()) {
            return null;
        }

        return $element->get();
    }

    /**
     * @param string $key
     * @param array<int, TaxesResultDto> $value
     * @return void
     * @throws InvalidArgumentException
     */
    public function setValue(string $key, mixed $value): void
    {
        $element = ($this->getCacheElement($key))
            ->set($value)
            ->expiresAfter(TaxesCacheConstant::getExpirationTime());

        if (false === $this->cache->save($element)) {
            $this->logger->error(TaxesCacheConstant::SAVE_ERROR_MESSAGE->value, [
                'key' => $key,
                'value' => $value,
            ]);
        }
    }

    /**
     * @param array<mixed> $keys
     * @return string
     */
    public function prepareCacheKey(array $keys): string
    {
        return TaxesCacheConstant::generateCacheKey(implode('_', array_filter(array_values($keys))));
    }
}
