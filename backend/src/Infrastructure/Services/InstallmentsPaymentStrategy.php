<?php

declare(strict_types=1);

namespace App\Infrastructure\Services;

use App\Domain\Entities\CheckoutResult;
use App\Domain\Services\PaymentStrategyInterface;
use InvalidArgumentException;

class InstallmentsPaymentStrategy implements PaymentStrategyInterface
{
    private const INTEREST_RATE = 0.01; // 1% ao mês
    private const MIN_INSTALLMENTS = 2;
    private const MAX_INSTALLMENTS = 12;

    public function __construct(private int $installments = 12)
    {
        if ($installments < self::MIN_INSTALLMENTS || $installments > self::MAX_INSTALLMENTS) {
            throw new InvalidArgumentException(
                "Parcelas devem ser entre " . self::MIN_INSTALLMENTS . " e " . self::MAX_INSTALLMENTS
            );
        }
    }

    public function calculateTotal(float $subtotal): CheckoutResult
    {
        $monthlyRate = self::INTEREST_RATE;
        $installments = $this->installments;
        
        // Cálculo de juros compostos para parcelamento
        $total = $subtotal * pow(1 + $monthlyRate, $installments);
        // Arredondamento bancário
        $total = round($total, 2, PHP_ROUND_HALF_EVEN);
        $interest = round($total - $subtotal, 2, PHP_ROUND_HALF_EVEN);

        return new CheckoutResult($subtotal, -$interest, $total, $installments);
    }

    public function getStrategyName(): string
    {
        return 'installments';
    }

    public function getInstallments(): int
    {
        return $this->installments;
    }

    public function getMonthlyPayment(): float
    {
        $total = $this->calculateTotal(100.0)->getTotal(); // Usar valor de exemplo para cálculo
        return $total / $this->installments;
    }
}
