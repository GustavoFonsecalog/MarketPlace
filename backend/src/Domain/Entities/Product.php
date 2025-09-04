<?php

declare(strict_types=1);

namespace App\Domain\Entities;

class Product
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly float $price,
        private readonly string $description
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function calculateTotal(int $quantity): float
    {
        return $this->price * $quantity;
    }
}
