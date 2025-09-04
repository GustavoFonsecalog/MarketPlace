<?php

declare(strict_types=1);

namespace App\Infrastructure\Services;

use App\Domain\Entities\CheckoutResult;
use App\Domain\Services\PaymentStrategyInterface;

class CreditCardPaymentStrategy implements PaymentStrategyInterface
{
    private const DISCOUNT_PERCENTAGE = 0.10; // 10% de desconto para pagamento à vista

    public function calculateTotal(float $subtotal): CheckoutResult
    {
        $discount = round($subtotal * self::DISCOUNT_PERCENTAGE, 2, PHP_ROUND_HALF_EVEN);
        $total = round($subtotal - $discount, 2, PHP_ROUND_HALF_EVEN);

        return new CheckoutResult($subtotal, $discount, $total, 1);
    }

    public function getStrategyName(): string
    {
        return 'credit_card';
    }
}
