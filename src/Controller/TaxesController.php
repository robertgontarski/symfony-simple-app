<?php

declare(strict_types=1);

namespace App\Controller;

use App\Decorator\TaxesCacheDecorator;
use App\Dto\DefaultResponseDto;
use App\Dto\TaxesInputDto;
use App\Exception\InvalidAdapterException;
use App\Exception\ValidationException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/taxes', name: 'taxes_')]
readonly class TaxesController
{
    /**
     * @param TaxesCacheDecorator $taxesCache
     */
    public function __construct(
        private TaxesCacheDecorator $taxesCache,
    ) {
    }

    #[Route('', name: 'get', methods: ['GET'])]
    /**
     * Handle GET request to retrieve taxes information.
     *
     * Endpoint expects two query parameters:
     * - country: ISO 3166-1 alpha-2 country code (required)
     * - state: Full name of the state (required if country is US or CA, otherwise optional)
     *
     * Example: /taxes?country=US&state=california
     *
     * Possible HTTP status codes:
     * - 200: Success, taxes information is returned in the response body
     * - 400: Validation error, request query parameters are invalid, missing or incorrect
     * - 500: Internal server error, unexpected error occurred during the request processing
     * - 504: Gateway timeout, external service is not available
     *
     * Expected response body:
     * - status: HTTP status code, (int)
     * - message: Response message (string)
     * - data: Response data, can be empty or contain information, (array)
     *
     * Example response body for unexpected error:
     * - { "status": 500, "message": "unexpected error", "data": [] }
     *
     * @param Request $request
     * @return DefaultResponseDto
     * @throws InvalidAdapterException
     * @throws ValidationException
     * @throws InvalidArgumentException
     */
    public function getTaxes(Request $request): DefaultResponseDto
    {
        $input = new TaxesInputDto(
            $request->get('country'),
            $request->get('state'),
        );

        $taxes = $this->taxesCache->getTaxes($input);

        return new DefaultResponseDto(Response::HTTP_OK, 'success', ['taxes' => $taxes]);
    }
}
