import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { Product, CartItem, PaymentStrategy } from '@/types'

export const useCartStore = defineStore('cart', () => {
  const items = ref<CartItem[]>([])
  const selectedPaymentStrategy = ref<PaymentStrategy>('pix')
  const selectedInstallments = ref<number>(1)

  const addItem = (product: Product, quantity: number = 1) => {
    const existingItem = items.value.find(item => item.product.id === product.id)
    
    if (existingItem) {
      existingItem.quantity += quantity
    } else {
      items.value.push({ product, quantity })
    }
  }

  const removeItem = (productId: number) => {
    const index = items.value.findIndex(item => item.product.id === productId)
    if (index > -1) {
      items.value.splice(index, 1)
    }
  }

  const updateQuantity = (productId: number, quantity: number) => {
    const item = items.value.find(item => item.product.id === productId)
    if (item) {
      if (quantity <= 0) {
        removeItem(productId)
      } else {
        item.quantity = quantity
      }
    }
  }

  const clearCart = () => {
    items.value = []
  }

  const setPaymentStrategy = (strategy: PaymentStrategy) => {
    selectedPaymentStrategy.value = strategy
    // Reset parcelas quando mudar estratégia
    if (strategy === 'installments') {
      selectedInstallments.value = 2
    } else {
      selectedInstallments.value = 1
    }
  }

  const setInstallments = (installments: number) => {
    selectedInstallments.value = installments
  }

  const subtotal = computed(() => {
    return items.value.reduce((total, item) => {
      return total + (item.product.price * item.quantity)
    }, 0)
  })

  const totalItems = computed(() => {
    return items.value.reduce((total, item) => total + item.quantity, 0)
  })

  return {
    items,
    selectedPaymentStrategy,
    selectedInstallments,
    addItem,
    removeItem,
    updateQuantity,
    clearCart,
    setPaymentStrategy,
    setInstallments,
    subtotal,
    totalItems
  }
}, {
  persist: true
})
