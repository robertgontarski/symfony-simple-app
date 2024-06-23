<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Constant\TaxesTypeResultConstant;
use App\Exception\InvalidTaxesTypeResultException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaxesTypeResultConstantTest extends KernelTestCase
{
    /**
     * @throws InvalidTaxesTypeResultException
     */
    public function testValidTypeReturns(): void
    {
        $this->assertEquals('VAT', TaxesTypeResultConstant::getTypeByValue('VAT')->value);
        $this->assertEquals('GST/HST', TaxesTypeResultConstant::getTypeByValue('GST/HST')->value);
        $this->assertEquals('PST', TaxesTypeResultConstant::getTypeByValue('PST')->value);
    }

    public function testInvalidTypeReturns(): void
    {
        $this->expectException(InvalidTaxesTypeResultException::class);
        TaxesTypeResultConstant::getTypeByValue('invalid');
    }
}
