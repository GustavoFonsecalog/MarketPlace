<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Product;
use App\Domain\Repositories\ProductRepositoryInterface;

class InMemoryProductRepository implements ProductRepositoryInterface
{
    private array $products;

    public function __construct()
    {
        $this->products = [
            1 => new Product(1, 'Smartphone Galaxy S23', 2999.99, 'Smartphone Samsung Galaxy S23 128GB com câmera de 108MP'),
            2 => new Product(2, 'Notebook Dell Inspiron', 4599.99, 'Notebook Dell Inspiron 15" Intel i5 8GB RAM 512GB SSD'),
            3 => new Product(3, 'Fone de Ouvido Sony', 299.99, 'Fone de ouvido sem fio Sony WH-1000XM4 com cancelamento de ruído'),
            4 => new Product(4, 'Smart TV LG 55"', 3499.99, 'Smart TV LG 55" 4K UHD com webOS e HDR'),
            5 => new Product(5, 'Tablet iPad Air', 5999.99, 'Tablet Apple iPad Air 10.9" 64GB com chip M1'),
            6 => new Product(6, 'iPhone 15 Pro', 8999.99, 'iPhone 15 Pro 256GB com Dynamic Island e câmera tripla'),
            7 => new Product(7, 'Acer Nitro 5 Gaming', 6999.99, 'Notebook Gamer Acer Nitro 5 Intel i7 RTX 4060 16GB RAM'),
            8 => new Product(8, 'Headset Gaming RGB', 599.99, 'Headset Gaming com microfone boom e iluminação RGB personalizável'),
            9 => new Product(9, 'Mouse Gaming RGB', 399.99, 'Mouse Gaming com sensor óptico 25K DPI e iluminação RGB'),
            10 => new Product(10, 'PC Gamer PICHAU', 12999.99, 'PC Gamer completo Intel i7 RTX 4070 32GB RAM 1TB NVMe'),
            11 => new Product(11, 'Câmera DSLR Canon', 3999.99, 'Câmera DSLR Canon EOS 2000D com lente 18-55mm'),
            12 => new Product(12, 'Câmera Action GoPro', 2499.99, 'Câmera Action GoPro Hero 11 Black 5.3K com estabilização'),
        ];
    }

    public function findById(int $id): ?Product
    {
        return $this->products[$id] ?? null;
    }

    public function findAll(): array
    {
        return array_values($this->products);
    }
}
