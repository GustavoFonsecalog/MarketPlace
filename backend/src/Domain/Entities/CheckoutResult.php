<?php

declare(strict_types=1);

namespace App\Domain\Entities;

class CheckoutResult
{
    public function __construct(
        private readonly float $subtotal,
        private readonly float $discount,
        private readonly float $total,
        private readonly ?int $installments = null
    ) {
    }

    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getInstallments(): ?int
    {
        return $this->installments;
    }

    public function toArray(): array
    {
        return [
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'total' => $this->total,
            'installments' => $this->installments,
        ];
    }
}
