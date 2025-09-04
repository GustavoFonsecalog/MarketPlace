<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Repositories;

use App\Infrastructure\Repositories\InMemoryProductRepository;
use PHPUnit\Framework\TestCase;

class InMemoryProductRepositoryTest extends TestCase
{
    private InMemoryProductRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryProductRepository();
    }

    public function testFindByIdReturnsProduct(): void
    {
        $product = $this->repository->findById(1);
        
        $this->assertNotNull($product);
        $this->assertEquals(1, $product->getId());
        $this->assertEquals('Smartphone Galaxy S23', $product->getName());
        $this->assertEquals(2999.99, $product->getPrice());
    }

    public function testFindByIdReturnsNullForInvalidId(): void
    {
        $product = $this->repository->findById(999);
        
        $this->assertNull($product);
    }

    public function testFindAllReturnsAllProducts(): void
    {
        $products = $this->repository->findAll();
        
        $this->assertIsArray($products);
        $this->assertCount(5, $products);
        $this->assertContainsOnlyInstancesOf('App\Domain\Entities\Product', $products);
    }
}
