<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Dto\TaxesInputDto;
use App\ExternalService\SeriousTax\TimeoutException;
use App\Handler\TaxesControllerExceptionHandler;
use App\Handler\TaxesExceptionHandler;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaxesControllerExceptionHandlerTest extends KernelTestCase
{
    private MockObject&LoggerInterface $logger;
    private TaxesExceptionHandler $handler;

    public function testHandleTimeoutException(): void
    {
        $inputDto = new TaxesInputDto('PL', '');
        $exception = new TimeoutException('timeout');

        $this->logger->expects(self::once())
            ->method('error')
            ->with('timeout while try to get taxes', [
                'exception' => $exception,
                ...$inputDto->toArray(),
            ]);

        $response = $this->handler->handle($exception, $inputDto);

        $this->assertEquals(504, $response->status);
        $this->assertEquals('timeout while try to get taxes', $response->message);
    }

    public function testHandleUnexpectedException(): void
    {
        $inputDto = new TaxesInputDto('LT', 'Vilnius');
        $exception = new \Exception('unexpected error');

        $this->logger->expects(self::once())
            ->method('error')
            ->with('unexpected error', [
                'exception' => $exception,
                ...$inputDto->toArray(),
            ]);

        $response = $this->handler->handle($exception, $inputDto);

        $this->assertEquals(500, $response->status);
        $this->assertEquals('unexpected error', $response->message);
    }

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->handler = new TaxesExceptionHandler($this->logger);
    }
}
