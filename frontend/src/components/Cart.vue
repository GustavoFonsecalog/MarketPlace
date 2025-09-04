<template>
  <div class="cart-container">
    <!-- Header do Carrinho -->
    <div class="cart-header">
      <h2 class="cart-title">CARRINHO <span class="cart-count">{{ cartStore.items.length }}</span></h2>
    </div>
    
    <div v-if="cartStore.items.length === 0" class="empty-cart">
      <div class="empty-icon">🛒</div>
      <p>Seu carrinho está vazio</p>
      <span>Adicione produtos para continuar</span>
    </div>
    
    <div v-else class="cart-content">
      <!-- Barra de Frete Grátis -->
      <div class="free-shipping-bar">
        <div class="shipping-text">
          <span v-if="!hasFreeShipping">
            Gaste mais R$ {{ formatPrice(freeShippingRemaining) }} e tenha frete grátis!
          </span>
          <span v-else class="free-shipping-achieved">
            🎉 Frete grátis ativado!
          </span>
        </div>
        <div class="shipping-progress">
          <div class="progress-bar">
            <div 
              class="progress-fill" 
              :style="{ width: `${shippingProgress}%` }"
            ></div>
          </div>
        </div>
      </div>
      
      <!-- Lista de Itens -->
      <div class="cart-items">
        <div 
          v-for="item in cartStore.items" 
          :key="item.product.id" 
          class="cart-item"
        >
          <div class="item-image">
            <img 
              :src="getProductImage(item.product)" 
              :alt="item.product.name"
              class="product-thumbnail"
            />
            <div class="image-placeholder" style="display: none;">
              <span>📱</span>
            </div>
          </div>
          
          <div class="item-details">
            <h4 class="item-name">{{ item.product.name }}</h4>
            <div class="item-price">{{ formatPrice(item.product.price) }}</div>
            <div class="item-category">{{ getCategoryDisplayName(item.product.category) }}</div>
            <div class="item-quantity">
              <span class="quantity-label">Quantidade:</span>
              <div class="quantity-controls">
                <button 
                  @click="decreaseQuantity(item.product.id)"
                  class="quantity-btn"
                  :disabled="item.quantity <= 1"
                >
                  -
                </button>
                <span class="quantity-value">{{ item.quantity }}</span>
                <button 
                  @click="increaseQuantity(item.product.id)"
                  class="quantity-btn"
                >
                  +
                </button>
              </div>
            </div>
            <button 
              @click="removeItem(item.product.id)"
              class="remove-link"
            >
              Remover
            </button>
          </div>
        </div>
      </div>
      
      <!-- Resumo e Total -->
      <div class="cart-summary">
        <div class="total-row">
          <span class="total-label">TOTAL</span>
          <span class="total-value">{{ formatPrice(cartStore.subtotal) }}</span>
        </div>
        
        <!-- Opções de Pagamento (expandível) -->
        <div class="payment-options">
          <button 
            @click="togglePaymentOptions"
            class="payment-toggle-btn"
          >
            {{ showPaymentOptions ? 'Ocultar' : 'Mostrar' }} opções de pagamento
          </button>
          
          <div v-if="showPaymentOptions" class="payment-content">
            <div class="payment-strategy">
              <label for="payment-strategy">Forma de Pagamento:</label>
              <select 
                id="payment-strategy"
                v-model="cartStore.selectedPaymentStrategy"
                @change="updatePaymentStrategy"
              >
                <template v-if="paymentMethods">
                  <option v-for="(method, key) in paymentMethods" :key="key" :value="key">
                    {{ method.name }} - {{ method.description }}
                  </option>
                </template>
                <template v-else>
                  <option value="pix">PIX (10% desconto)</option>
                  <option value="credit_card">Cartão de Crédito</option>
                  <option value="installments">Parcelado (12x com juros)</option>
                </template>
              </select>
            </div>
            
            <!-- Seleção de Parcelas -->
            <div v-if="cartStore.selectedPaymentStrategy === 'installments'" class="installments-selector">
              <label for="installments">Número de Parcelas:</label>
              <select 
                id="installments"
                v-model="cartStore.selectedInstallments"
                @change="updateInstallments"
              >
                <option v-for="installment in availableInstallments" :key="installment" :value="installment">
                  {{ installment }}x
                </option>
              </select>
            </div>
            
            <div v-if="checkoutResult" class="summary-details">
                              <div class="summary-row">
                  <span>Subtotal:</span>
                  <span>{{ formatPrice(cartStore.subtotal) }}</span>
                </div>
                
                <div class="summary-row">
                  <span>{{ getDiscountLabel() }}:</span>
                  <span :class="getDiscountClass()">
                    {{ getDiscountPrefix() }}{{ formatPrice(Math.abs(checkoutResult.discount)) }}
                  </span>
                </div>
                
                <div class="summary-row total">
                  <span>Total:</span>
                  <span>{{ formatPrice(checkoutResult.total) }}</span>
                </div>
                
                <div v-if="checkoutResult?.installments" class="installments-info">
                  <p>Em {{ checkoutResult.installments }}x de {{ formatPrice(checkoutResult.total / checkoutResult.installments) }}</p>
                </div>
            </div>
            
            <button 
              @click="processCheckout(true)"
              class="checkout-btn"
              :disabled="isProcessing"
            >
              {{ isProcessing ? 'Processando...' : 'Finalizar Compra' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useCartStore } from '@/stores/cart'
import { checkoutService } from '@/services/api'
import { formatPrice, formatPriceNoSymbol } from '@/utils'
import { useToastStore } from '@/stores/toast'
import type { CheckoutResponse, PaymentMethodsResponse } from '@/types'
import notebookDell from '@/assets/notebook-dell.png'
import foneSony from '@/assets/fone-sone.png'
import samsung from '@/assets/samsung.png'
import tvLg from '@/assets/tv-lg.jpg'
import ipad from '@/assets/ipad.png'
import iphone from '@/assets/iphone.png'
import acerNitro from '@/assets/acer-nitro.png'
import foneGaming from '@/assets/fone-gaming.png'
import mouse from '@/assets/mouse.png'
import pcGaming from '@/assets/pc-gaming.png'

const cartStore = useCartStore()
const toast = useToastStore()
const checkoutResult = ref<CheckoutResponse | null>(null)
const isProcessing = ref(false)
const showPaymentOptions = ref(false)
const paymentMethods = ref<PaymentMethodsResponse | null>(null)

// Configurações de frete grátis
const FREE_SHIPPING_THRESHOLD = 250.00

// Computed properties para frete grátis
const hasFreeShipping = computed(() => cartStore.subtotal >= FREE_SHIPPING_THRESHOLD)
const freeShippingRemaining = computed(() => Math.max(0, FREE_SHIPPING_THRESHOLD - cartStore.subtotal))
const shippingProgress = computed(() => Math.min(100, (cartStore.subtotal / FREE_SHIPPING_THRESHOLD) * 100))

// Computed properties para parcelas
const availableInstallments = computed(() => {
  if (!paymentMethods.value || cartStore.selectedPaymentStrategy !== 'installments') return []
  const method = paymentMethods.value.installments
  if (Array.isArray(method.installments)) {
    return method.installments
  }
  return []
})

// Carregar métodos de pagamento disponíveis
const loadPaymentMethods = async () => {
  try {
    paymentMethods.value = await checkoutService.getPaymentMethods()
    console.log('Métodos de pagamento carregados:', paymentMethods.value)
  } catch (error) {
    console.error('Erro ao carregar métodos de pagamento:', error)
  }
}

const updatePaymentStrategy = () => {
  checkoutResult.value = null
  // Calcular checkout automaticamente quando mudar estratégia
  if (cartStore.items.length > 0) {
    // Para cartão parcelado, só calcular se tiver parcelas selecionadas
    if (cartStore.selectedPaymentStrategy === 'installments' && cartStore.selectedInstallments > 1) {
      processCheckout()
    } else if (cartStore.selectedPaymentStrategy !== 'installments') {
      // Para PIX e cartão à vista, calcular imediatamente
      processCheckout()
    }
  }
}

const updateInstallments = () => {
  checkoutResult.value = null
  // Calcular checkout automaticamente quando mudar parcelas
  if (cartStore.items.length > 0 && cartStore.selectedPaymentStrategy === 'installments') {
    processCheckout()
  }
}

const increaseQuantity = (productId: number) => {
  const item = cartStore.items.find(item => item.product.id === productId)
  if (item) {
    cartStore.updateQuantity(productId, item.quantity + 1)
  }
}

const decreaseQuantity = (productId: number) => {
  const item = cartStore.items.find(item => item.product.id === productId)
  if (item && item.quantity > 1) {
    cartStore.updateQuantity(productId, item.quantity - 1)
  }
}

const removeItem = (productId: number) => {
  cartStore.removeItem(productId)
  checkoutResult.value = null
}

const clearCart = () => {
  cartStore.clearCart()
  checkoutResult.value = null
}

const processCheckout = async (finalize: boolean = false) => {
  if (cartStore.items.length === 0) return
  
  isProcessing.value = true
  
  try {
    const request: any = {
      items: cartStore.items.map(item => ({
        product_id: item.product.id,
        quantity: item.quantity
      })),
      payment_method: cartStore.selectedPaymentStrategy
    }
    if (cartStore.selectedPaymentStrategy === 'installments') {
      request.installments = cartStore.selectedInstallments
    }
    
    const result = await checkoutService.processCheckout(request)
    checkoutResult.value = result

    if (finalize) {
      // Persistir pedido simples no localStorage
      const raw = localStorage.getItem('orders')
      const orders = raw ? JSON.parse(raw) : []
      const newOrder = {
        id: `${Date.now()}`,
        date: new Date().toISOString(),
        items: cartStore.items.map(i => ({ 
          productId: i.product.id, 
          name: i.product.name,
          quantity: i.quantity, 
          price: i.product.price 
        })),
        total: result.total,
        installments: result.installments ?? 1,
        payment_method: cartStore.selectedPaymentStrategy
      }
      orders.unshift(newOrder)
      localStorage.setItem('orders', JSON.stringify(orders))
      // Limpar carrinho e fechar sidebar
      cartStore.clearCart()
      showPaymentOptions.value = false
      // Toast de sucesso
      toast.success('Pedido Finalizado', 'Pedido finalizado com sucesso!')
      // Emitir evento global simples para fechar overlay (App.vue controla)
      const closeEvent = new CustomEvent('close-cart')
      window.dispatchEvent(closeEvent)
    }
  } catch (error) {
    console.error('Erro ao processar checkout:', error)
    alert('Erro ao processar checkout. Tente novamente.')
  } finally {
    isProcessing.value = false
  }
}

const getDiscountLabel = (): string => {
  if (!checkoutResult.value) return ''
  return checkoutResult.value.discount > 0 ? 'Desconto' : 'Juros'
}

const getDiscountClass = (): string => {
  if (!checkoutResult.value) return ''
  return checkoutResult.value.discount > 0 ? 'discount' : 'interest'
}

const getDiscountPrefix = (): string => {
  if (!checkoutResult.value) return ''
  return checkoutResult.value.discount > 0 ? '' : '+'
}

const togglePaymentOptions = () => {
  showPaymentOptions.value = !showPaymentOptions.value
  // Se acabou de abrir, tenta calcular conforme seleção atual
  if (showPaymentOptions.value && cartStore.items.length > 0) {
    if (cartStore.selectedPaymentStrategy === 'installments') {
      if (cartStore.selectedInstallments > 1) {
        processCheckout()
      }
    } else {
      processCheckout()
    }
  }
}

const viewCart = () => {
  // Aqui você pode implementar a navegação para a página do carrinho
  console.log('Navegar para página do carrinho')
}

// Carregar métodos de pagamento quando o componente for montado
onMounted(() => {
  loadPaymentMethods()
})

// Watcher para monitorar mudanças no carrinho e recalcular checkout
watch(
  () => [cartStore.items, cartStore.items.map(item => item.quantity)],
  () => {
    // Recalcular checkout se já tiver resultado e itens no carrinho
    if (checkoutResult.value && cartStore.items.length > 0) {
      // Aguardar um pouco para evitar múltiplas chamadas
      setTimeout(() => {
        if (cartStore.selectedPaymentStrategy === 'installments' && cartStore.selectedInstallments > 1) {
          processCheckout()
        } else if (cartStore.selectedPaymentStrategy !== 'installments') {
          processCheckout()
        }
      }, 100)
    }
  },
  { deep: true }
)

const getProductImage = (product: any) => {
  if (product.image) {
    const imageMap: Record<string, any> = {
      'samsung': samsung,
      'notebook-dell': notebookDell,
      'fone-sone': foneSony,
      'tv-lg': tvLg,
      'ipad': ipad,
      'iphone': iphone,
      'acer-nitro': acerNitro,
      'fone-gaming': foneGaming,
      'mouse': mouse,
      'pc-gaming': pcGaming
    }
    return imageMap[product.image] || null
  }
  
  const productName = product.name.toLowerCase()
  if (productName.includes('notebook') || productName.includes('dell')) return notebookDell
  if (productName.includes('fone') || productName.includes('sony')) return foneSony
  if (productName.includes('samsung') || productName.includes('galaxy')) return samsung
  if (productName.includes('tv') || productName.includes('lg')) return tvLg
  if (productName.includes('ipad') || productName.includes('tablet')) return ipad
  if (productName.includes('iphone')) return iphone
  if (productName.includes('acer') || productName.includes('nitro')) return acerNitro
  if (productName.includes('gaming') && productName.includes('fone')) return foneGaming
  if (productName.includes('mouse')) return mouse
  if (productName.includes('pc gamer') || productName.includes('pichau')) return pcGaming
  
  return null
}

const getCategoryDisplayName = (category?: string): string => {
  const categoryMap: Record<string, string> = {
    'smartphones': 'Smartphone',
    'laptops': 'Notebook',
    'headphones': 'Fone de Ouvido',
    'tvs': 'Smart TV',
    'tablets': 'Tablet',
    'gaming': 'Gaming',
    'cameras': 'Câmera',
    'accessories': 'Acessório'
  }
  const key = category || ''
  return categoryMap[key] || key
}
</script>

<style scoped>
.cart-container {
  flex: 1;
  height: 100%;
  display: flex;
  flex-direction: column;
  background: var(--cart-bg);
}

/* Garantir contraste em TODOS os elementos do carrinho */
.cart-container * {
  color: var(--text-primary);
}

.cart-container h1, .cart-container h2, .cart-container h3, .cart-container h4, .cart-container h5, .cart-container h6 {
  color: var(--text-primary);
}

.cart-container p, .cart-container span, .cart-container div, .cart-container label {
  color: var(--text-primary);
}

.cart-container button {
  color: var(--text-primary);
}

.cart-container input, .cart-container select, .cart-container textarea {
  color: var(--text-primary);
  background-color: var(--input-bg);
  border-color: var(--input-border);
}

/* Header do Carrinho */
.cart-header {
  padding: 20px 20px 16px 20px;
  border-bottom: 1px solid var(--cart-border);
  background: var(--cart-bg);
}

.cart-title {
  font-size: 18px;
  font-weight: 700;
  color: var(--text-primary);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 8px;
}

.cart-count {
  background: var(--primary-color);
  color: var(--text-inverse);
  padding: 2px 8px;
  border-radius: 12px;
  font-size: 14px;
  font-weight: 600;
}

/* Carrinho Vazio */
.empty-cart {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 40px 20px;
  text-align: center;
}

.empty-icon {
  font-size: 48px;
  margin-bottom: 16px;
  opacity: 0.5;
}

.empty-cart p {
  font-size: 16px;
  font-weight: 600;
  color: var(--text-primary);
  margin: 0 0 8px 0;
}

.empty-cart span {
  font-size: 14px;
  color: var(--text-secondary);
}

.cart-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow-y: auto;
}

/* Barra de Frete Grátis */
.free-shipping-bar {
  padding: 16px 20px;
  background: var(--bg-tertiary);
  border-bottom: 1px solid var(--border-primary);
}

.shipping-text {
  font-size: 14px;
  color: var(--text-secondary);
  margin-bottom: 12px;
  text-align: center;
}

.free-shipping-achieved {
  color: var(--success-color);
  font-weight: 600;
}

.shipping-progress {
  display: flex;
  align-items: center;
}

.progress-bar {
  width: 100%;
  height: 4px;
  background: var(--border-secondary);
  border-radius: 2px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: var(--primary-color);
  transition: width 0.3s ease;
}

.cart-items {
  flex: 1;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.cart-item {
  display: flex;
  gap: 16px;
  padding: 16px;
  border: 1px solid var(--border-primary);
  border-radius: 8px;
  background: var(--card-bg);
  box-shadow: var(--shadow-sm);
  align-items: flex-start;
  min-height: 110px;
}

/* Imagem do Produto */
.item-image {
  flex-shrink: 0;
  width: 80px;
  height: 80px;
  border-radius: 8px;
  overflow: hidden;
  background: var(--bg-tertiary);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.product-thumbnail {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.image-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  opacity: 0.5;
}

/* Detalhes do Item */
.item-details {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 8px;
  min-height: 70px;
  justify-content: space-between;
}

.item-name {
  font-size: 16px;
  font-weight: 600;
  color: var(--text-primary);
  margin: 0;
  line-height: 1.3;
  min-height: 42px;
  display: flex;
  align-items: flex-start;
}

.item-price {
  font-size: 16px;
  font-weight: 600;
  color: var(--text-primary);
  min-height: 24px;
  display: flex;
  align-items: center;
}

.item-category {
  font-size: 12px;
  color: var(--text-secondary);
  text-transform: capitalize;
  background: var(--bg-tertiary);
  padding: 2px 8px;
  border-radius: 12px;
  align-self: flex-start;
  min-height: 20px;
  display: flex;
  align-items: center;
}

.item-quantity {
  display: flex;
  align-items: center;
  gap: 12px;
  min-height: 32px;
}

.quantity-label {
  font-size: 14px;
  color: var(--text-secondary);
}

.quantity-controls {
  display: flex;
  align-items: center;
  gap: 8px;
  height: 32px;
}

.quantity-btn {
  width: 32px;
  height: 32px;
  border: 1px solid var(--border-secondary);
  background: var(--btn-secondary-bg);
  color: var(--text-primary);
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
  flex-shrink: 0;
}

.quantity-btn:hover:not(:disabled) {
  background: var(--btn-secondary-hover);
  border-color: var(--border-primary);
}

.quantity-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.quantity-value {
  font-weight: 600;
  color: var(--text-primary);
  min-width: 32px;
  text-align: center;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--bg-tertiary);
  border-radius: 4px;
  font-size: 14px;
}

.remove-link {
  background: none;
  border: none;
  color: var(--error-color);
  font-size: 14px;
  cursor: pointer;
  padding: 0;
  text-decoration: underline;
  align-self: flex-start;
  min-height: 20px;
  display: flex;
  align-items: center;
}

.remove-link:hover {
  color: var(--error-color);
  filter: brightness(0.8);
}

/* CSS já atualizado acima */

/* CSS já atualizado acima */

.cart-summary {
  padding: 20px;
  border-top: 1px solid var(--border-primary);
  background: var(--card-bg);
  flex-shrink: 0;
}

.total-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 16px;
  border-bottom: 1px solid var(--border-primary);
  min-height: 60px;
}

.total-label {
  font-size: 18px;
  font-weight: 700;
  color: var(--text-primary);
}

.total-value {
  font-size: 18px;
  font-weight: 700;
  color: var(--text-primary);
}

.view-cart-btn {
  width: 100%;
  padding: 16px;
  background: var(--primary-color);
  color: var(--text-inverse);
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s ease;
  margin-bottom: 16px;
  height: 56px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.view-cart-btn:hover {
  background: var(--primary-hover);
}

.payment-options {
  border-top: 1px solid var(--border-primary);
  padding-top: 16px;
}

.payment-toggle-btn {
  width: 100%;
  background: var(--btn-secondary-bg);
  border: 1px solid var(--border-secondary);
  padding: 12px;
  border-radius: 6px;
  color: var(--text-secondary);
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s ease;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.payment-toggle-btn:hover {
  background: var(--btn-secondary-hover);
  border-color: var(--border-primary);
}

.payment-content {
  margin-top: 16px;
  padding-top: 16px;
  border-top: 1px solid var(--border-primary);
}

.payment-strategy {
  margin-bottom: 20px;
}

.payment-strategy label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: var(--text-primary);
  font-size: 14px;
}

.payment-strategy select {
  width: 100%;
  padding: 12px;
  border: 1px solid var(--border-secondary);
  border-radius: 6px;
  font-size: 14px;
  background: var(--input-bg);
  color: var(--text-primary);
  height: 48px;
  display: flex;
  align-items: center;
}

.installments-selector {
  margin-top: 16px;
  margin-bottom: 20px;
}

.installments-selector label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: var(--text-primary);
  font-size: 14px;
}

.installments-selector select {
  width: 100%;
  padding: 12px;
  border: 1px solid var(--border-secondary);
  border-radius: 6px;
  font-size: 14px;
  background: var(--input-bg);
  color: var(--text-primary);
  height: 48px;
  display: flex;
  align-items: center;
}

.summary-details {
  margin-bottom: 20px;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 12px;
  padding: 8px 0;
  font-size: 14px;
}

.summary-row span:first-child {
  color: var(--text-secondary);
}

.summary-row span:last-child {
  font-weight: 600;
  color: var(--text-primary);
}

.summary-row.total {
  font-weight: 700;
  font-size: 16px;
  color: var(--text-primary);
  border-top: 1px solid var(--border-primary);
  padding-top: 12px;
  margin-top: 12px;
}

.discount {
  color: var(--success-color);
}

.interest {
  color: var(--error-color);
}

.installments-info {
  margin: 16px 0;
  padding: 12px;
  background: var(--bg-tertiary);
  border-radius: 6px;
  text-align: center;
  color: var(--text-secondary);
  font-size: 14px;
}

.checkout-btn {
  width: 100%;
  padding: 16px;
  background: var(--success-color);
  color: var(--text-inverse);
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s ease;
  height: 56px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.checkout-btn:hover:not(:disabled) {
  background: var(--success-color);
  filter: brightness(0.9);
}

.checkout-btn:disabled {
  background: var(--text-tertiary);
  cursor: not-allowed;
}

/* Mobile-first responsive design já implementado acima */
</style>
