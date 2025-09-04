# Utilitários do Sistema

Este diretório contém funções utilitárias reutilizáveis para formatação, cálculos e outras operações comuns.

## 📁 Estrutura

```
utils/
├── index.ts      # Funções principais
├── types.ts      # Tipos TypeScript
└── README.md     # Esta documentação
```

## 🚀 Funções Disponíveis

### Formatação de Moeda

#### `formatCurrency(value, options)`
Formata um valor numérico para moeda brasileira (BRL).

```typescript
import { formatCurrency } from '@/utils'

// Formatação padrão: R$ 1.234,56
formatCurrency(1234.56)

// Sem símbolo da moeda: 1.234,56
formatCurrency(1234.56, { showSymbol: false })

// Sem centavos: R$ 1.235
formatCurrency(1234.56, { showCents: false })
```

#### `formatPrice(price)`
Formata preço para exibição simples (R$ X,XX).

```typescript
import { formatPrice } from '@/utils'

formatPrice(1234.56) // "R$ 1.234,56"
```

#### `formatPriceNoSymbol(price)`
Formata preço sem símbolo da moeda (X,XX).

```typescript
import { formatPriceNoSymbol } from '@/utils'

formatPriceNoSymbol(1234.56) // "1.234,56"
```

#### `formatPriceInteger(price)`
Formata preço inteiro (R$ X).

```typescript
import { formatPriceInteger } from '@/utils'

formatPriceInteger(1234.56) // "R$ 1.235"
```

### Preços com Desconto

#### `formatPriceWithDiscount(originalPrice, currentPrice)`
Formata preço com desconto, retornando objeto com todas as informações.

```typescript
import { formatPriceWithDiscount } from '@/utils'

const discount = formatPriceWithDiscount(100, 80)
// {
//   original: "R$ 100,00",
//   current: "R$ 80,00",
//   discount: "R$ 20,00",
//   discountPercentage: 20,
//   hasDiscount: true
// }
```

### Parcelamento

#### `formatInstallments(totalPrice, installments, interestRate)`
Formata informações de parcelamento, com ou sem juros.

```typescript
import { formatInstallments } from '@/utils'

// Sem juros
formatInstallments(1000, 10)
// {
//   total: "R$ 1.000,00",
//   installmentValue: "R$ 100,00",
//   installments: 10,
//   hasInterest: false
// }

// Com juros (1% ao mês)
formatInstallments(1000, 12, 0.01)
// {
//   total: "R$ 1.126,83",
//   installmentValue: "R$ 93,90",
//   installments: 12,
//   hasInterest: true,
//   interestAmount: "R$ 126,83"
// }
```

### Outras Formatações

#### `formatNumber(value, locale)`
Formata número com separadores de milhares.

```typescript
import { formatNumber } from '@/utils'

formatNumber(1234567) // "1.234.567"
```

#### `formatPercentage(value, locale)`
Formata porcentagem.

```typescript
import { formatPercentage } from '@/utils'

formatPercentage(0.15) // "15%"
```

#### `formatDate(date, locale)`
Formata data.

```typescript
import { formatDate } from '@/utils'

formatDate(new Date()) // "25/12/2024"
```

#### `formatDateTime(date, locale)`
Formata data e hora.

```typescript
import { formatDateTime } from '@/utils'

formatDateTime(new Date()) // "25/12/2024 14:30"
```

### Cálculos

#### `calculateDiscountPercentage(originalPrice, currentPrice)`
Calcula percentual de desconto.

```typescript
import { calculateDiscountPercentage } from '@/utils'

calculateDiscountPercentage(100, 80) // 20
```

#### `isValidPrice(value)`
Valida se um valor é um preço válido.

```typescript
import { isValidPrice } from '@/utils'

isValidPrice(100) // true
isValidPrice(-50) // false
isValidPrice(NaN) // false
```

#### `roundPrice(price)`
Arredonda preço para 2 casas decimais.

```typescript
import { roundPrice } from '@/utils'

roundPrice(123.456) // 123.46
```

### Formatação de Produtos

#### `formatWeight(weight)`
Formata peso (gramas para kg quando apropriado).

```typescript
import { formatWeight } from '@/utils'

formatWeight(500) // "500g"
formatWeight(1500) // "1.5kg"
```

#### `formatDimensions(dimensions)`
Formata dimensões de produtos.

```typescript
import { formatDimensions } from '@/utils'

formatDimensions({ width: 30, height: 20, depth: 15 })
// "30 × 20 × 15 cm"
```

## 🎯 Casos de Uso

### 1. Formatação de Preços em Produtos
```typescript
import { formatPrice, formatPriceWithDiscount } from '@/utils'

// Preço simples
const price = formatPrice(product.price)

// Preço com desconto
const discount = formatPriceWithDiscount(product.originalPrice, product.currentPrice)
```

### 2. Formatação de Preços no Carrinho
```typescript
import { formatPrice, formatInstallments } from '@/utils'

// Total do carrinho
const total = formatPrice(cartTotal)

// Parcelamento
const installments = formatInstallments(cartTotal, 12, 0.01)
```

### 3. Formatação de Descontos
```typescript
import { calculateDiscountPercentage } from '@/utils'

// Badge de desconto
const discountPercent = calculateDiscountPercentage(originalPrice, currentPrice)
const badgeText = `-${discountPercent}%`
```

## 🔧 Configuração

### Locale Padrão
O sistema usa `pt-BR` como locale padrão para formatação brasileira.

### Personalização
Você pode personalizar as opções de formatação passando objetos de configuração:

```typescript
formatCurrency(1234.56, {
  showSymbol: false,
  showCents: false,
  locale: 'en-US'
})
```

## 📱 Responsividade

Todas as funções são compatíveis com:
- ✅ TypeScript
- ✅ Vue 3 Composition API
- ✅ SSR (Server-Side Rendering)
- ✅ Tree-shaking (imports específicos)

## 🚀 Performance

- **Formatação nativa**: Usa `Intl.NumberFormat` para performance máxima
- **Memoização**: Recomenda-se usar `computed()` para valores que não mudam
- **Tree-shaking**: Importe apenas o que você precisa

## 🔍 Exemplos Práticos

### Produto Card
```typescript
<template>
  <div class="product-price">
    <span v-if="hasDiscount" class="original-price">
      {{ formatPrice(originalPrice) }}
    </span>
    <span class="current-price">{{ formatPrice(product.price) }}</span>
  </div>
  
  <div v-if="hasDiscount" class="discount-badge">
    -{{ calculateDiscountPercentage(originalPrice, product.price) }}%
  </div>
</template>

<script setup>
import { formatPrice, calculateDiscountPercentage } from '@/utils'
</script>
```

### Carrinho
```typescript
<template>
  <div class="cart-total">
    <span>Total:</span>
    <span>{{ formatPrice(cartTotal) }}</span>
  </div>
  
  <div v-if="installments" class="installments">
    Em {{ installments.installments }}x de {{ installments.installmentValue }}
  </div>
</template>

<script setup>
import { formatPrice, formatInstallments } from '@/utils'

const installments = computed(() => 
  formatInstallments(cartTotal.value, 12, 0.01)
)
</script>
```

## 📚 Referências

- [Intl.NumberFormat - MDN](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Intl/NumberFormat)
- [Intl.DateTimeFormat - MDN](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Intl/DateTimeFormat)
- [Vue 3 Composition API](https://vuejs.org/guide/extras/composition-api-faq.html)
