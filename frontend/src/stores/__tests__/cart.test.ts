import { describe, it, expect, beforeEach } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useCartStore } from '../cart'
import type { Product } from '@/types'

describe('Cart Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })

  const mockProduct: Product = {
    id: 1,
    name: 'Test Product',
    price: 100.00,
    description: 'Test Description'
  }

  it('should add item to cart', () => {
    const store = useCartStore()
    store.addItem(mockProduct, 2)
    
    expect(store.items).toHaveLength(1)
    expect(store.items[0].product).toEqual(mockProduct)
    expect(store.items[0].quantity).toBe(2)
  })

  it('should increase quantity for existing item', () => {
    const store = useCartStore()
    store.addItem(mockProduct, 1)
    store.addItem(mockProduct, 2)
    
    expect(store.items).toHaveLength(1)
    expect(store.items[0].quantity).toBe(3)
  })

  it('should remove item from cart', () => {
    const store = useCartStore()
    store.addItem(mockProduct, 1)
    store.removeItem(1)
    
    expect(store.items).toHaveLength(0)
  })

  it('should update item quantity', () => {
    const store = useCartStore()
    store.addItem(mockProduct, 1)
    store.updateQuantity(1, 5)
    
    expect(store.items[0].quantity).toBe(5)
  })

  it('should remove item when quantity is 0', () => {
    const store = useCartStore()
    store.addItem(mockProduct, 1)
    store.updateQuantity(1, 0)
    
    expect(store.items).toHaveLength(0)
  })

  it('should clear cart', () => {
    const store = useCartStore()
    store.addItem(mockProduct, 1)
    store.clearCart()
    
    expect(store.items).toHaveLength(0)
  })

  it('should calculate subtotal correctly', () => {
    const store = useCartStore()
    store.addItem(mockProduct, 2)
    
    expect(store.subtotal).toBe(200.00)
  })

  it('should calculate total items correctly', () => {
    const store = useCartStore()
    store.addItem(mockProduct, 2)
    
    expect(store.totalItems).toBe(2)
  })

  it('should set payment strategy', () => {
    const store = useCartStore()
    store.setPaymentStrategy('credit_card')
    
    expect(store.selectedPaymentStrategy).toBe('credit_card')
  })
})
