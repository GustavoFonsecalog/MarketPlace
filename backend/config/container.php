<?php

declare(strict_types=1);

use App\Application\Services\CheckoutService;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Domain\Services\PaymentStrategyFactory;
use App\Infrastructure\Repositories\InMemoryProductRepository;
use App\Infrastructure\Services\PixPaymentStrategy;
use App\Infrastructure\Services\CreditCardPaymentStrategy;
use App\Interface\Controllers\CheckoutController;
use App\Interface\Requests\CheckoutRequest;

return [
    // Repositories
    ProductRepositoryInterface::class => \DI\create(InMemoryProductRepository::class),
    
    // Payment Strategies
    PixPaymentStrategy::class => \DI\create(PixPaymentStrategy::class),
    CreditCardPaymentStrategy::class => \DI\create(CreditCardPaymentStrategy::class),
    
    // Services
    PaymentStrategyFactory::class => \DI\autowire(PaymentStrategyFactory::class),
    CheckoutService::class => \DI\autowire(CheckoutService::class),
    
    // Controllers
    CheckoutController::class => \DI\autowire(CheckoutController::class),
    
    // Request Validators
    CheckoutRequest::class => \DI\create(CheckoutRequest::class),
];
