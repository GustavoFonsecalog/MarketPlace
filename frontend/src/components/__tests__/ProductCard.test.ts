import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import ProductCard from '../ProductCard.vue'
import type { Product } from '@/types'

describe('ProductCard', () => {
  const mockProduct: Product = {
    id: 1,
    name: 'Test Product',
    price: 100.00,
    description: 'Test Description'
  }

  beforeEach(() => {
    setActivePinia(createPinia())
  })

  it('should render product information correctly', () => {
    const wrapper = mount(ProductCard, {
      props: { product: mockProduct }
    })

    expect(wrapper.text()).toContain('Test Product')
    expect(wrapper.text()).toContain('Test Description')
    expect(wrapper.text()).toContain('R$ 100,00')
  })

  it('should show "Adicionar ao Carrinho" button when product is not in cart', () => {
    const wrapper = mount(ProductCard, {
      props: { product: mockProduct }
    })

    expect(wrapper.text()).toContain('Adicionar ao Carrinho')
  })

  it('should show "No Carrinho" button when product is in cart', () => {
    const wrapper = mount(ProductCard, {
      props: { product: mockProduct }
    })

    // Simulate adding to cart
    const store = wrapper.vm.$pinia._s.get('cart')
    store.addItem(mockProduct, 1)

    // Re-render to see updated state
    wrapper.vm.$forceUpdate()

    expect(wrapper.text()).toContain('No Carrinho')
  })

  it('should call addToCart when button is clicked', async () => {
    const wrapper = mount(ProductCard, {
      props: { product: mockProduct }
    })

    const button = wrapper.find('.add-to-cart-btn')
    await button.trigger('click')

    const store = wrapper.vm.$pinia._s.get('cart')
    expect(store.items).toHaveLength(1)
    expect(store.items[0].product).toEqual(mockProduct)
  })
})
