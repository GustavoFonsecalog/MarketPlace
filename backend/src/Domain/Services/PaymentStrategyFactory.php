<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Infrastructure\Services\CreditCardPaymentStrategy;
use App\Infrastructure\Services\InstallmentsPaymentStrategy;
use App\Infrastructure\Services\PixPaymentStrategy;
use InvalidArgumentException;

class PaymentStrategyFactory
{
    public function __construct(
        private readonly PixPaymentStrategy $pixStrategy,
        private readonly CreditCardPaymentStrategy $creditCardStrategy
    ) {
    }

    public function createStrategy(string $strategyName, int $installments = 1): PaymentStrategyInterface
    {
        return match ($strategyName) {
            'pix' => $this->pixStrategy,
            'credit_card' => $this->creditCardStrategy,
            'installments' => new InstallmentsPaymentStrategy($installments),
            default => throw new InvalidArgumentException("Estratégia de pagamento inválida: {$strategyName}")
        };
    }

    public function getAvailableStrategies(): array
    {
        return [
            'pix' => [
                'name' => 'PIX',
                'description' => 'Pagamento à vista com 10% de desconto',
                'installments' => 1
            ],
            'credit_card' => [
                'name' => 'Cartão de Crédito à Vista',
                'description' => 'Pagamento à vista com 10% de desconto',
                'installments' => 1
            ],
            'installments' => [
                'name' => 'Cartão de Crédito Parcelado',
                'description' => 'Parcelamento de 2x até 12x com juros de 1% ao mês',
                'installments' => range(2, 12)
            ]
        ];
    }
}
