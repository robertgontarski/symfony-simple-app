<?php

declare(strict_types=1);

namespace App\Handler;

use App\Constant\TaxesControllerConstant;
use App\Dto\DefaultResponseDto;
use App\Dto\TaxesInputDto;
use App\Exception\ValidationException;
use App\ExternalService\SeriousTax\TimeoutException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

readonly class TaxesExceptionHandler extends AbstractHandler
{
    /**
     * @inheritDoc
     */
    public function __construct(LoggerInterface $logger)
    {
        parent::__construct($logger);
    }

    /**
     * @param Throwable $e
     * @param TaxesInputDto $dto
     * @return DefaultResponseDto
     */
    public function handle(Throwable $e, TaxesInputDto $dto): DefaultResponseDto
    {
        return $this->processError($e, $dto);
    }

    /**
     * @param Throwable $e
     * @param TaxesInputDto $dto
     * @return DefaultResponseDto
     */
    private function processError(Throwable $e, TaxesInputDto $dto): DefaultResponseDto
    {
        return match (true) {
            $e instanceof TimeoutException => $this->handleTimeoutException($e, $dto),
            $e instanceof ValidationException => $this->handleValidationException(),
            default => $this->handleUnexpectedException($e, $dto),
        };
    }

    /**
     * @param TimeoutException $e
     * @param TaxesInputDto $dto
     * @return DefaultResponseDto
     */
    private function handleTimeoutException(TimeoutException $e, TaxesInputDto $dto): DefaultResponseDto
    {
        $this->logger->error(TaxesControllerConstant::TIMEOUT_ERROR_MESSAGE->value, [
            'exception' => $e,
            ...$dto->toArray(),
        ]);

        return new DefaultResponseDto(
            Response::HTTP_GATEWAY_TIMEOUT,
            TaxesControllerConstant::TIMEOUT_ERROR_MESSAGE->value,
        );
    }

    /**
     * @return DefaultResponseDto
     */
    private function handleValidationException(): DefaultResponseDto
    {
        return new DefaultResponseDto(
            Response::HTTP_BAD_REQUEST,
            TaxesControllerConstant::VALIDATION_ERROR_MESSAGE->value,
        );
    }

    private function handleUnexpectedException(Throwable $e, TaxesInputDto $dto): DefaultResponseDto
    {
        $this->logger->error(TaxesControllerConstant::UNEXPECTED_ERROR_MESSAGE->value, [
            'exception' => $e,
            ...$dto->toArray(),
        ]);

        return new DefaultResponseDto(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            TaxesControllerConstant::UNEXPECTED_ERROR_MESSAGE->value,
        );
    }
}
