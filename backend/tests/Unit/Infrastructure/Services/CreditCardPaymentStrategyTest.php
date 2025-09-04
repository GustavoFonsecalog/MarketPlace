<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Services;

use App\Domain\Entities\CheckoutResult;
use App\Infrastructure\Services\CreditCardPaymentStrategy;
use PHPUnit\Framework\TestCase;

class CreditCardPaymentStrategyTest extends TestCase
{
    private CreditCardPaymentStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new CreditCardPaymentStrategy();
    }

    public function testCalculateTotalWithoutDiscount(): void
    {
        $subtotal = 100.00;
        $result = $this->strategy->calculateTotal($subtotal);

        $this->assertInstanceOf(CheckoutResult::class, $result);
        $this->assertEquals(100.00, $result->getSubtotal());
        $this->assertEquals(0.0, $result->getDiscount());
        $this->assertEquals(100.00, $result->getTotal());
        $this->assertNull($result->getInstallments());
    }

    public function testGetStrategyName(): void
    {
        $this->assertEquals('credit_card', $this->strategy->getStrategyName());
    }
}
