<?php

declare(strict_types=1);

namespace App\Infrastructure\Services;

use App\Domain\Entities\CheckoutResult;
use App\Domain\Services\PaymentStrategyInterface;

class PixPaymentStrategy implements PaymentStrategyInterface
{
    private const DISCOUNT_PERCENTAGE = 0.10; // 10% de desconto

    public function calculateTotal(float $subtotal): CheckoutResult
    {
        $discount = $subtotal * self::DISCOUNT_PERCENTAGE;
        $total = $subtotal - $discount;

        return new CheckoutResult($subtotal, $discount, $total);
    }

    public function getStrategyName(): string
    {
        return 'pix';
    }
}
