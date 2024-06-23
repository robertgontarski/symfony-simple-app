<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Dto\TaxesInputDto;
use App\Handler\TaxesExceptionHandler;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(event: 'kernel.exception', method: 'onKernelException')]
readonly final class ExceptionListener
{
    public function __construct(
        private TaxesExceptionHandler $handler,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $input = new TaxesInputDto(
            $event->getRequest()->get('country'),
            $event->getRequest()->get('state'),
        );

        $exception = $event->getThrowable();
        $response = $this->handler->handle($exception, $input);

        $event->setResponse(new JsonResponse($response, $response->status));
    }
}
