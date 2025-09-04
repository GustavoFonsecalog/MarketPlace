/**
 * Utilitários para formatação e cálculos
 */

import type { 
  CurrencyFormatOptions, 
  PriceWithDiscount, 
  InstallmentInfo, 
  Dimensions 
} from './types'

/**
 * Formata um valor numérico para moeda brasileira (BRL)
 * @param value - Valor numérico para formatar
 * @param options - Opções de formatação
 * @returns String formatada em moeda brasileira
 */
export const formatCurrency = (
  value: number,
  options: CurrencyFormatOptions = {}
): string => {
  const {
    showSymbol = true,
    showCents = true,
    locale = 'pt-BR'
  } = options

  const formatter = new Intl.NumberFormat(locale, {
    style: showSymbol ? 'currency' : 'decimal',
    currency: 'BRL',
    minimumFractionDigits: showCents ? 2 : 0,
    maximumFractionDigits: showCents ? 2 : 0
  })

  return formatter.format(value)
}

/**
 * Formata preço para exibição simples (R$ X,XX)
 * @param price - Preço em número
 * @returns String formatada
 */
export const formatPrice = (price: number): string => {
  return formatCurrency(price, { showSymbol: true, showCents: true })
}

/**
 * Formata preço sem símbolo da moeda (X,XX)
 * @param price - Preço em número
 * @returns String formatada
 */
export const formatPriceNoSymbol = (price: number): string => {
  return formatCurrency(price, { showSymbol: false, showCents: true })
}

/**
 * Formata preço inteiro (R$ X)
 * @param price - Preço em número
 * @returns String formatada
 */
export const formatPriceInteger = (price: number): string => {
  return formatCurrency(price, { showSymbol: true, showCents: false })
}

/**
 * Formata preço com desconto (preço original e atual)
 * @param originalPrice - Preço original
 * @param currentPrice - Preço atual
 * @returns Objeto com preços formatados
 */
export const formatPriceWithDiscount = (
  originalPrice: number,
  currentPrice: number
): PriceWithDiscount => {
  const discount = originalPrice - currentPrice
  const discountPercentage = Math.round((discount / originalPrice) * 100)

  return {
    original: formatPrice(originalPrice),
    current: formatPrice(currentPrice),
    discount: formatPrice(discount),
    discountPercentage,
    hasDiscount: discount > 0
  }
}

/**
 * Formata preço parcelado
 * @param totalPrice - Preço total
 * @param installments - Número de parcelas
 * @param interestRate - Taxa de juros mensal (0.01 = 1%)
 * @returns Objeto com informações de parcelamento
 */
export const formatInstallments = (
  totalPrice: number,
  installments: number,
  interestRate: number = 0
): InstallmentInfo => {
  if (interestRate === 0) {
    const installmentValue = totalPrice / installments
    return {
      total: formatPrice(totalPrice),
      installmentValue: formatPrice(installmentValue),
      installments,
      hasInterest: false
    }
  }

  // Cálculo com juros compostos
  const monthlyRate = interestRate
  const installmentValue = (totalPrice * monthlyRate * Math.pow(1 + monthlyRate, installments)) / 
                          (Math.pow(1 + monthlyRate, installments) - 1)
  const totalWithInterest = installmentValue * installments

  return {
    total: formatPrice(totalWithInterest),
    installmentValue: formatPrice(installmentValue),
    installments,
    hasInterest: true,
    interestAmount: formatPrice(totalWithInterest - totalPrice)
  }
}

/**
 * Formata número para exibição com separadores
 * @param value - Número para formatar
 * @param locale - Localização (padrão: pt-BR)
 * @returns String formatada
 */
export const formatNumber = (value: number, locale: string = 'pt-BR'): string => {
  return new Intl.NumberFormat(locale).format(value)
}

/**
 * Formata porcentagem
 * @param value - Valor decimal (0.15 = 15%)
 * @param locale - Localização (padrão: pt-BR)
 * @returns String formatada
 */
export const formatPercentage = (value: number, locale: string = 'pt-BR'): string => {
  return new Intl.NumberFormat(locale, {
    style: 'percent',
    minimumFractionDigits: 0,
    maximumFractionDigits: 1
  }).format(value)
}

/**
 * Formata data para exibição
 * @param date - Data para formatar
 * @param locale - Localização (padrão: pt-BR)
 * @returns String formatada
 */
export const formatDate = (date: Date, locale: string = 'pt-BR'): string => {
  return new Intl.DateTimeFormat(locale, {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  }).format(date)
}

/**
 * Formata data e hora para exibição
 * @param date - Data para formatar
 * @param locale - Localização (padrão: pt-BR)
 * @returns String formatada
 */
export const formatDateTime = (date: Date, locale: string = 'pt-BR'): string => {
  return new Intl.DateTimeFormat(locale, {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date)
}

/**
 * Calcula desconto percentual
 * @param originalPrice - Preço original
 * @param currentPrice - Preço atual
 * @returns Percentual de desconto
 */
export const calculateDiscountPercentage = (originalPrice: number, currentPrice: number): number => {
  if (originalPrice <= 0) return 0
  return Math.round(((originalPrice - currentPrice) / originalPrice) * 100)
}

/**
 * Valida se um valor é um preço válido
 * @param value - Valor para validar
 * @returns Boolean indicando se é válido
 */
export const isValidPrice = (value: number): boolean => {
  return typeof value === 'number' && 
         !isNaN(value) && 
         isFinite(value) && 
         value >= 0
}

/**
 * Arredonda preço para 2 casas decimais
 * @param price - Preço para arredondar
 * @returns Preço arredondado
 */
export const roundPrice = (price: number): number => {
  return Math.round(price * 100) / 100
}

/**
 * Formata peso para exibição
 * @param weight - Peso em gramas
 * @returns String formatada
 */
export const formatWeight = (weight: number): string => {
  if (weight < 1000) {
    return `${weight}g`
  }
  return `${(weight / 1000).toFixed(1)}kg`
}

/**
 * Formata dimensões para exibição
 * @param dimensions - Objeto com largura, altura e profundidade em cm
 * @returns String formatada
 */
export const formatDimensions = (dimensions: Dimensions): string => {
  const { width, height, depth } = dimensions
  return `${width} × ${height} × ${depth} cm`
}

// Exportar tipos
export type {
  CurrencyFormatOptions,
  PriceWithDiscount,
  InstallmentInfo,
  Dimensions
}
