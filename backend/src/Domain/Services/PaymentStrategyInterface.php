<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Entities\CheckoutResult;

interface PaymentStrategyInterface
{
    public function calculateTotal(float $subtotal): CheckoutResult;
    
    public function getStrategyName(): string;
}
