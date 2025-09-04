<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Entities\CartItem;
use App\Domain\Entities\Product;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Domain\Services\PaymentStrategyFactory;
use App\Domain\Services\PaymentStrategyInterface;
use InvalidArgumentException;

class CheckoutService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly PaymentStrategyFactory $paymentStrategyFactory
    ) {
    }

    public function processCheckout(array $items, string $paymentStrategy, int $installments = 1): array
    {
        $cartItems = $this->createCartItems($items);
        $subtotal = $this->roundMoney($this->calculateSubtotal($cartItems));
        
        $strategy = $this->paymentStrategyFactory->createStrategy($paymentStrategy, $installments);
        $result = $strategy->calculateTotal($subtotal);

        // Normalizar arredondamento em todas as saídas
        $response = $result->toArray();
        $response['subtotal'] = $this->roundMoney((float) $response['subtotal']);
        $response['discount'] = $this->roundMoney((float) $response['discount']);
        $response['total'] = $this->roundMoney((float) $response['total']);

        return $response;
    }

    public function getAvailablePaymentMethods(): array
    {
        return $this->paymentStrategyFactory->getAvailableStrategies();
    }

    private function createCartItems(array $items): array
    {
        $cartItems = [];
        
        foreach ($items as $item) {
            if (!isset($item['product_id'], $item['quantity'])) {
                throw new InvalidArgumentException('Item deve conter product_id e quantity');
            }
            
            $product = $this->productRepository->findById($item['product_id']);
            if ($product === null) {
                throw new InvalidArgumentException("Produto com ID {$item['product_id']} não encontrado");
            }
            
            if ($item['quantity'] <= 0) {
                throw new InvalidArgumentException('Quantidade deve ser maior que zero');
            }
            
            $cartItems[] = new CartItem($product, $item['quantity']);
        }
        
        return $cartItems;
    }

    private function calculateSubtotal(array $cartItems): float
    {
        return array_reduce($cartItems, function (float $total, CartItem $item) {
            return $total + $item->getSubtotal();
        }, 0.0);
    }

    private function roundMoney(float $value): float
    {
        // Arredondamento bancário com 2 casas
        return round($value, 2, PHP_ROUND_HALF_EVEN);
    }
}
