<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Services;

use App\Application\Services\CheckoutService;
use App\Domain\Services\PaymentStrategyFactory;
use App\Infrastructure\Repositories\InMemoryProductRepository;
use App\Infrastructure\Services\CreditCardPaymentStrategy;
use App\Infrastructure\Services\PixPaymentStrategy;
use PHPUnit\Framework\TestCase;

final class PaymentCalculationsTest extends TestCase
{
    private CheckoutService $service;

    protected function setUp(): void
    {
        $this->service = new CheckoutService(
            new InMemoryProductRepository(),
            new PaymentStrategyFactory(
                new PixPaymentStrategy(),
                new CreditCardPaymentStrategy()
            )
        );
    }

    public function testPixHasTenPercentDiscount(): void
    {
        $items = [[ 'product_id' => 1, 'quantity' => 1 ]];
        $result = $this->service->processCheckout($items, 'pix');

        $expectedDiscount = round($result['subtotal'] * 0.10, 2, PHP_ROUND_HALF_EVEN);
        self::assertEquals($expectedDiscount, $result['discount']);
        self::assertEquals(round($result['subtotal'] - $expectedDiscount, 2, PHP_ROUND_HALF_EVEN), $result['total']);
    }

    public function testCreditCardOneShotHasTenPercentDiscount(): void
    {
        $items = [[ 'product_id' => 1, 'quantity' => 1 ]];
        $result = $this->service->processCheckout($items, 'credit_card');

        $expectedDiscount = round($result['subtotal'] * 0.10, 2, PHP_ROUND_HALF_EVEN);
        self::assertEquals($expectedDiscount, $result['discount']);
        self::assertEquals(round($result['subtotal'] - $expectedDiscount, 2, PHP_ROUND_HALF_EVEN), $result['total']);
    }
}


