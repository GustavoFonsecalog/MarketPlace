/**
 * Tipos para as funções de utilitários
 */

export interface CurrencyFormatOptions {
  showSymbol?: boolean
  showCents?: boolean
  locale?: string
}

export interface PriceWithDiscount {
  original: string
  current: string
  discount: string
  discountPercentage: number
  hasDiscount: boolean
}

export interface InstallmentInfo {
  total: string
  installmentValue: string
  installments: number
  hasInterest: boolean
  interestAmount?: string
}

export interface Dimensions {
  width: number
  height: number
  depth: number
}
