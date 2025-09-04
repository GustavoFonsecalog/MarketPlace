<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Services;

use App\Domain\Entities\CheckoutResult;
use App\Infrastructure\Services\PixPaymentStrategy;
use PHPUnit\Framework\TestCase;

class PixPaymentStrategyTest extends TestCase
{
    private PixPaymentStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new PixPaymentStrategy();
    }

    public function testCalculateTotalWithDiscount(): void
    {
        $subtotal = 100.00;
        $result = $this->strategy->calculateTotal($subtotal);

        $this->assertInstanceOf(CheckoutResult::class, $result);
        $this->assertEquals(100.00, $result->getSubtotal());
        $this->assertEquals(10.00, $result->getDiscount());
        $this->assertEquals(90.00, $result->getTotal());
        $this->assertNull($result->getInstallments());
    }

    public function testGetStrategyName(): void
    {
        $this->assertEquals('pix', $this->strategy->getStrategyName());
    }
}
