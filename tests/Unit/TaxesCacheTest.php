<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Cache\TaxesCache;
use App\Dto\TaxesProcessDto;
use App\Dto\TaxesResultDto;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaxesCacheTest extends KernelTestCase
{
    private MockObject&CacheItemPoolInterface $cacheItemPool;
    private MockObject&CacheItemInterface $cacheItem;
    private MockObject&LoggerInterface $logger;
    private TaxesCache $taxesCache;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->cacheItemPool = $this->createMock(CacheItemPoolInterface::class);
        $this->cacheItem = $this->createMock(CacheItemInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->taxesCache = new TaxesCache($this->cacheItemPool, $this->logger);
    }

    public function testGenerateKey(): void
    {
        $dto = new TaxesProcessDto('US', 'california');
        $expected = '6e434c0ca88023fed52cfd94861d5c8d';

        $result = $this->taxesCache->prepareCacheKey($dto->toArray());

        $this->assertEquals($expected, $result);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testGetTaxesReturnsNullWhenCacheIsEmpty(): void
    {
        $dto = new TaxesProcessDto('US', 'california');

        $this->cacheItem->expects($this->once())
            ->method('isHit')
            ->willReturn(false);

        $this->cacheItemPool->expects($this->once())
            ->method('getItem')
            ->willReturn($this->cacheItem);

        $result = $this->taxesCache->getValue($this->taxesCache->prepareCacheKey($dto->toArray()));

        $this->assertEquals(null, $result);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testGetTaxesReturnsCachedValue(): void
    {
        $dto = new TaxesProcessDto('US', 'california');
        $cachedValue = [
            new TaxesResultDto('VAT', 8.875),
        ];

        $this->cacheItem->expects($this->once())
            ->method('isHit')
            ->willReturn(true);

        $this->cacheItem->expects($this->once())
            ->method('get')
            ->willReturn($cachedValue);

        $this->cacheItemPool->expects($this->once())
            ->method('getItem')
            ->willReturn($this->cacheItem);

        $result = $this->taxesCache->getValue($this->taxesCache->prepareCacheKey($dto->toArray()));

        $this->assertEquals($cachedValue, $result);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSetTaxesCachesValue(): void
    {
        $dto = new TaxesProcessDto('US', 'california');
        $valueToCache = [
            new TaxesResultDto('VAT', 8.875),
        ];

        $this->cacheItem->expects($this->once())
            ->method('set')
            ->with($valueToCache);

        $this->cacheItemPool->expects($this->once())
            ->method('getItem')
            ->willReturn($this->cacheItem);

        $this->cacheItemPool->expects($this->once())
            ->method('save')
            ->with($this->cacheItem);

        $this->taxesCache->setValue($this->taxesCache->prepareCacheKey($dto->toArray()), $valueToCache);
    }
}
