<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Dto\TaxesInputDto;
use App\Validator\TaxesInputValidator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;

class TaxesInputValidatorTest extends KernelTestCase
{
    private TaxesInputValidator $validator;

    protected function setUp(): void
    {
        $symfonyValidator = Validation::createValidator();
        $this->validator = new TaxesInputValidator($symfonyValidator);
    }

    public function testValidateWithNotSupportedCountry(): void
    {
        $dto = new TaxesInputDto('XX', null);

        $errors = $this->validator->validate($dto);

        $this->assertEquals(['country is not supported'], $errors);
    }

    public function testValidateWithSupportedCountry(): void
    {
        $dto = new TaxesInputDto(
            'LT',
            null
        );

        $errors = $this->validator->validate($dto);

        $this->assertEquals([], $errors);
    }

    public function testValidateWithSupportedCountryAndNotReqState(): void
    {
        $dto = new TaxesInputDto(
            'US',
            null
        );

        $errors = $this->validator->validate($dto);

        $this->assertEquals([
            'state is required'
        ], $errors);
    }

    public function testValidateWithSupportedCountryAndReqState(): void
    {
        $dto = new TaxesInputDto(
            'US',
            'random state'
        );

        $errors = $this->validator->validate($dto);

        $this->assertEquals([], $errors);
    }
}
