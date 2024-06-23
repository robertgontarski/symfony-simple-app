<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Dto\DefaultResponseDto;
use JetBrains\PhpStorm\NoReturn;
use RuntimeException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;

#[AsEventListener(event: 'kernel.view', method: 'onKernelResponse')]
readonly final class ResponseListener
{
    public function onKernelResponse(ViewEvent $event): void
    {
        $data = $event->getControllerResult();

        if (false === $data instanceof DefaultResponseDto) {
            throw new RuntimeException('Controller must return DefaultResponseDto');
        }

        $event->setResponse(new JsonResponse($data, $data->status));
    }
}
