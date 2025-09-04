<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testProductCreation(): void
    {
        $product = new Product(1, 'Test Product', 100.00, 'Test Description');

        $this->assertEquals(1, $product->getId());
        $this->assertEquals('Test Product', $product->getName());
        $this->assertEquals(100.00, $product->getPrice());
        $this->assertEquals('Test Description', $product->getDescription());
    }

    public function testCalculateTotal(): void
    {
        $product = new Product(1, 'Test Product', 100.00, 'Test Description');

        $this->assertEquals(200.00, $product->calculateTotal(2));
        $this->assertEquals(100.00, $product->calculateTotal(1));
        $this->assertEquals(0.00, $product->calculateTotal(0));
    }
}
