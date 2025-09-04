<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Services;

use App\Domain\Services\PaymentStrategyFactory;
use App\Infrastructure\Services\CreditCardPaymentStrategy;
use App\Infrastructure\Services\InstallmentsPaymentStrategy;
use App\Infrastructure\Services\PixPaymentStrategy;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PaymentStrategyFactoryTest extends TestCase
{
    private PaymentStrategyFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new PaymentStrategyFactory(
            $this->createMock(PixPaymentStrategy::class),
            $this->createMock(CreditCardPaymentStrategy::class),
            $this->createMock(InstallmentsPaymentStrategy::class)
        );
    }

    public function testCreatePixStrategy(): void
    {
        $strategy = $this->factory->createStrategy('pix');
        $this->assertInstanceOf(PixPaymentStrategy::class, $strategy);
    }

    public function testCreateCreditCardStrategy(): void
    {
        $strategy = $this->factory->createStrategy('credit_card');
        $this->assertInstanceOf(CreditCardPaymentStrategy::class, $strategy);
    }

    public function testCreateInstallmentsStrategy(): void
    {
        $strategy = $this->factory->createStrategy('installments');
        $this->assertInstanceOf(InstallmentsPaymentStrategy::class, $strategy);
    }

    public function testCreateInvalidStrategy(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Estratégia de pagamento inválida: invalid');
        
        $this->factory->createStrategy('invalid');
    }
}
