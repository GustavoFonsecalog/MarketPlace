<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Product;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?Product;
    
    public function findAll(): array;
}
