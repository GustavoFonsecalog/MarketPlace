# Carrinho de Compras Simples

Projeto fullstack implementando um carrinho de compras com arquitetura limpa, persistência local e estratégias de pagamento flexíveis.

## 🏗️ Arquitetura

### Backend (PHP + Slim)
- **Domain Layer**: Entidades e regras de negócio
- **Application Layer**: Casos de uso e serviços
- **Infrastructure Layer**: Implementações concretas
- **Interface Layer**: Controllers e rotas

### Frontend (Vue 3 + Vite + TypeScript + Pinia)
- **Components**: Interface do usuário
- **Store**: Gerenciamento de estado com Pinia
- **Utils**: Funções auxiliares
- **Tests**: Testes unitários com Vitest

## 🚀 Como Executar

### Backend
```bash
cd backend
composer install
composer run lint
composer run test
php -S localhost:8000 -t public
```

### Frontend
```bash
cd frontend
npm install
npm run lint
npm run test
npm run dev
```

## 🧪 Testes e Qualidade

### Backend
- **Lint**: PHP_CodeSniffer (PSR-12)
- **Tests**: PHPUnit
- **Comando**: `composer run test`

### Frontend
- **Lint**: ESLint + Prettier
- **Tests**: Vitest
- **Comando**: `npm run test`

## 📋 Checklist de Aceite

- [x] Persistência do carrinho em localStorage (não fixo em código)
- [x] Código modular, SRP, SOLID - padrão sênior
- [x] Backend com Strategy Pattern e camadas bem separadas
- [x] Testes automatizados configurados
- [x] CI com GitHub Actions
- [x] README completo com instruções
- [x] Sem banco de dados (produtos fixos)
- [x] Frontend exibe subtotal, desconto/juros, parcelas e total
- [x] Arquitetura limpa e organizada

## 🔧 Decisões Arquiteturais

### Backend
- **Slim Framework**: Micro-framework leve e performático
- **Strategy Pattern**: Para diferentes métodos de pagamento
- **Clean Architecture**: Separação clara de responsabilidades
- **PSR-12**: Padrões de codificação PHP

### Frontend
- **Vue 3 Composition API**: API moderna e reativa
- **Pinia**: Gerenciamento de estado com persistência
- **TypeScript**: Tipagem estática para maior confiabilidade
- **Vite**: Build tool rápido para desenvolvimento

## 📡 Endpoints

Todas as rotas expostas sob `/api` (proxy do Vite aponta para o backend em `127.0.0.1:8000`).

### POST /api/checkout
Calcula o total com base na estratégia de pagamento selecionada.

Aceita dois formatos de payload (compatibilidade):

1) Formato interno
```json
{
  "items": [
    { "product_id": 1, "quantity": 2 }
  ],
  "payment_method": "pix|credit_card|installments",
  "installments": 3
}
```

2) Formato do desafio
```json
{
  "produtos": [
    { "nome": "Fone Bluetooth", "valor": 100.00, "quantidade": 2 },
    { "nome": "Mouse Gamer", "valor": 150.00, "quantidade": 1 }
  ],
  "metodo_pagamento": "PIX|CARTAO_CREDITO",
  "parcelas": 3
}
```

**Response (exemplo):**
```json
{
  "subtotal": 100.00,
  "discount": 10.00,
  "total": 90.00,
  "installments": 1
}
```

## 🧮 Regras de Pagamento

- PIX: 10% de desconto
- Cartão de Crédito (1x): 10% de desconto
- Cartão de Crédito Parcelado (2x–12x): juros compostos de 1% a.m.

Fórmula de juros compostos: \( M = P \cdot (1 + 0.01)^n \)

Arredondamento: bancário (2 casas) aplicado em subtotal, desconto/juros e total.

## 💾 Persistência Local

O carrinho é persistido automaticamente no localStorage usando Pinia, garantindo que o estado seja mantido entre sessões e recarregamentos da página.

## 🎯 Foco na Qualidade

Este projeto demonstra:
- Arquitetura limpa e bem organizada
- Separação de responsabilidades
- Testes automatizados
- Padrões de codificação consistentes
- Documentação clara e objetiva
