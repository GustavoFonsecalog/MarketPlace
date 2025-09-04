<?php

declare(strict_types=1);

namespace App\Interface\Requests;

use Psr\Http\Message\ServerRequestInterface;
use InvalidArgumentException;

class CheckoutRequest
{
    private array $data;

    public function __construct(ServerRequestInterface $request)
    {
        $this->data = $request->getParsedBody() ?? [];
        // Normalização para aceitar também o payload do desafio
        // { produtos: [{ nome, valor, quantidade }], metodo_pagamento, parcelas }
        if (!empty($this->data)) {
            $this->normalizeAlternatePayload();
        }
        $this->validate();
    }

    public function getItems(): array
    {
        return $this->data['items'] ?? [];
    }

    /**
     * Retorna itens em formato alternativo (produtos) quando presentes
     * Cada item contém: nome (string), valor (float), quantidade (int)
     */
    public function getAlternateProducts(): array
    {
        return $this->data['produtos'] ?? [];
    }

    public function getPaymentStrategy(): string
    {
        return $this->data['payment_method'] ?? '';
    }

    public function getInstallments(): int
    {
        $installments = $this->data['installments'] ?? 1;
        
        if ($this->getPaymentStrategy() === 'installments') {
            if ($installments < 2 || $installments > 12) {
                throw new InvalidArgumentException('Parcelas devem ser entre 2 e 12 para pagamento parcelado');
            }
        }
        
        return (int) $installments;
    }

    private function validate(): void
    {
        if (empty($this->data['items']) && empty($this->data['produtos'])) {
            throw new InvalidArgumentException('Items ou produtos são obrigatórios');
        }

        if (empty($this->data['payment_method'])) {
            throw new InvalidArgumentException('Método de pagamento é obrigatório');
        }

        $validStrategies = ['pix', 'credit_card', 'installments'];
        if (!in_array($this->data['payment_method'], $validStrategies, true)) {
            throw new InvalidArgumentException('Método de pagamento inválido');
        }

        if (!empty($this->data['items'])) {
            foreach ($this->data['items'] as $item) {
                if (!isset($item['product_id']) || !isset($item['quantity'])) {
                    throw new InvalidArgumentException('Cada item deve conter product_id e quantity');
                }

                if (!is_numeric($item['product_id']) || !is_numeric($item['quantity'])) {
                    throw new InvalidArgumentException('product_id e quantity devem ser numéricos');
                }

                if ($item['quantity'] <= 0) {
                    throw new InvalidArgumentException('Quantity deve ser maior que zero');
                }
            }
        }

        if (!empty($this->data['produtos'])) {
            foreach ($this->data['produtos'] as $produto) {
                if (!isset($produto['nome'], $produto['valor'], $produto['quantidade'])) {
                    throw new InvalidArgumentException('Cada produto deve conter nome, valor e quantidade');
                }
                if (!is_string($produto['nome']) || !is_numeric($produto['valor']) || !is_numeric($produto['quantidade'])) {
                    throw new InvalidArgumentException('Campos de produto com tipos inválidos');
                }
                if ($produto['quantidade'] <= 0 || $produto['valor'] < 0) {
                    throw new InvalidArgumentException('Quantidade deve ser > 0 e valor >= 0');
                }
            }
        }
    }

    /**
     * Normaliza o payload alternativo do desafio para o formato interno.
     */
    private function normalizeAlternatePayload(): void
    {
        // Mapear metodo_pagamento para payment_method interno
        if (!isset($this->data['payment_method']) && isset($this->data['metodo_pagamento'])) {
            $metodo = strtoupper((string) $this->data['metodo_pagamento']);
            $parcelas = isset($this->data['parcelas']) ? (int) $this->data['parcelas'] : 1;
            $this->data['payment_method'] = match ($metodo) {
                'PIX' => 'pix',
                'CARTAO_CREDITO' => ($parcelas >= 2 ? 'installments' : 'credit_card'),
                default => 'pix'
            };
            if (!isset($this->data['installments'])) {
                $this->data['installments'] = max(1, $parcelas);
            }
        }

        // Se vier "produtos", mantemos em $this->data['produtos'] para fluxo alternativo
        // Itens no formato interno (com product_id) continuam em $this->data['items']
    }
}
