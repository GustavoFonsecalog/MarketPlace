# Instruções de Instalação e Execução

## Pré-requisitos

- PHP 8.1 ou superior
- Composer
- Node.js 18 ou superior
- npm ou yarn

## Backend

### 1. Instalar dependências
```bash
cd backend
composer install
```

### 2. Executar testes
```bash
composer run test
```

### 3. Executar linting
```bash
composer run lint
```

### 4. Iniciar servidor
```bash
php -S localhost:8000 -t public
```

## Frontend

### 1. Instalar dependências
```bash
cd frontend
npm install
```

### 2. Executar testes
```bash
npm run test
```

### 3. Executar linting
```bash
npm run lint
```

### 4. Iniciar servidor de desenvolvimento
```bash
npm run dev
```

### 5. Build para produção
```bash
npm run build
```

## Estrutura do Projeto

```
carrinho-compras-simples/
├── backend/                 # Backend PHP + Slim
│   ├── src/
│   │   ├── Domain/         # Entidades e regras de negócio
│   │   ├── Application/    # Casos de uso e serviços
│   │   ├── Infrastructure/ # Implementações concretas
│   │   └── Interface/      # Controllers e requests
│   ├── tests/              # Testes PHPUnit
│   ├── config/             # Configurações
│   └── public/             # Ponto de entrada
├── frontend/               # Frontend Vue 3 + Vite
│   ├── src/
│   │   ├── components/     # Componentes Vue
│   │   ├── stores/         # Stores Pinia
│   │   ├── services/       # Serviços de API
│   │   └── types/          # Tipos TypeScript
│   └── tests/              # Testes Vitest
└── .github/workflows/      # CI/CD
```

## Funcionalidades

### Backend
- ✅ Arquitetura limpa com camadas bem separadas
- ✅ Strategy Pattern para métodos de pagamento
- ✅ Validação de requests
- ✅ Testes unitários
- ✅ PSR-12 compliance
- ✅ Injeção de dependência

### Frontend
- ✅ Vue 3 Composition API
- ✅ TypeScript
- ✅ Pinia com persistência local
- ✅ Componentes reutilizáveis
- ✅ Testes unitários
- ✅ Design responsivo

### Estratégias de Pagamento
- **PIX**: 10% de desconto
- **Cartão de Crédito (1x)**: 10% de desconto
- **Cartão de Crédito Parcelado (2x–12x)**: Juros compostos de 1% ao mês

## Endpoints da API

### POST /api/checkout
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
  "subtotal": 5999.98,
  "discount": 599.99,
  "total": 5399.99,
  "installments": 1
}
```

## Persistência Local

O carrinho é automaticamente salvo no localStorage usando Pinia, garantindo que:
- O estado persiste entre sessões
- Os dados são restaurados ao recarregar a página
- As preferências de pagamento são mantidas

## Testes

### Backend
```bash
cd backend
composer run test
```

### Frontend
```bash
cd frontend
npm run test
```

## Linting e Formatação

### Backend
```bash
cd backend
composer run lint
composer run lint:fix
```

### Frontend
```bash
cd frontend
npm run lint
npm run format
```

## CI/CD

O projeto inclui GitHub Actions configurado para:
- Executar testes em múltiplas versões do PHP e Node.js
- Verificar qualidade do código
- Validar builds
- Executar linting

## Checklist de Aceite

- [x] Persistência do carrinho em localStorage
- [x] Código modular e SOLID
- [x] Backend com Strategy Pattern
- [x] Testes automatizados
- [x] CI configurado
- [x] README completo
- [x] Arquitetura limpa
- [x] Frontend responsivo
- [x] Estratégias de pagamento funcionais
