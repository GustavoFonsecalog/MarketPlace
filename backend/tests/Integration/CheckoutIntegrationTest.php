<?php

declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use App\Application\Services\CheckoutService;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Domain\Services\PaymentStrategyFactory;
use App\Infrastructure\Repositories\InMemoryProductRepository;
use App\Infrastructure\Services\PixPaymentStrategy;
use App\Infrastructure\Services\CreditCardPaymentStrategy;
use App\Infrastructure\Services\InstallmentsPaymentStrategy;

class CheckoutIntegrationTest extends TestCase
{
    private CheckoutService $checkoutService;

    protected function setUp(): void
    {
        $productRepository = new InMemoryProductRepository();
        $paymentStrategyFactory = new PaymentStrategyFactory(
            new PixPaymentStrategy(),
            new CreditCardPaymentStrategy()
        );

        $this->checkoutService = new CheckoutService(
            $productRepository,
            $paymentStrategyFactory
        );
    }

    public function testCheckoutWithPixStrategy(): void
    {
        $items = [
            ['product_id' => 1, 'quantity' => 2]
        ];

        $result = $this->checkoutService->processCheckout($items, 'pix');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('subtotal', $result);
        $this->assertArrayHasKey('discount', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertGreaterThan(0, $result['subtotal']);
        $this->assertGreaterThan(0, $result['discount']);
        $this->assertLessThan($result['subtotal'], $result['total']);
    }

    public function testCheckoutWithCreditCardStrategy(): void
    {
        $items = [
            ['product_id' => 1, 'quantity' => 1]
        ];

        $result = $this->checkoutService->processCheckout($items, 'credit_card');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('subtotal', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertLessThan($result['subtotal'], $result['total']);
    }

    public function testCheckoutWithInstallmentsStrategy(): void
    {
        $items = [
            ['product_id' => 1, 'quantity' => 1]
        ];

        $result = $this->checkoutService->processCheckout($items, 'installments', 12);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('subtotal', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertArrayHasKey('installments', $result);
        $this->assertGreaterThan($result['subtotal'], $result['total']);
        $this->assertEquals(12, $result['installments']);
    }
}
