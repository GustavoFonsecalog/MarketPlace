<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Services;

use App\Domain\Entities\CheckoutResult;
use App\Infrastructure\Services\InstallmentsPaymentStrategy;
use PHPUnit\Framework\TestCase;

class InstallmentsPaymentStrategyTest extends TestCase
{
    private InstallmentsPaymentStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new InstallmentsPaymentStrategy();
    }

    public function testCalculateTotalWithInterest(): void
    {
        $subtotal = 100.00;
        $result = $this->strategy->calculateTotal($subtotal);

        $this->assertInstanceOf(CheckoutResult::class, $result);
        $this->assertEquals(100.00, $result->getSubtotal());
        $this->assertLessThan(0, $result->getDiscount()); // Juros são negativos
        $this->assertGreaterThan(100.00, $result->getTotal());
        $this->assertEquals(12, $result->getInstallments());
    }

    public function testGetStrategyName(): void
    {
        $this->assertEquals('installments', $this->strategy->getStrategyName());
    }
}
