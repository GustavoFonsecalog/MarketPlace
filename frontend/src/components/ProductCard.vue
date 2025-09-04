<template>
  <div :class="['product-card', `product-card--${viewMode}`]">
    <!-- Imagem do Produto -->
    <div class="product-image">
      <img 
        v-if="productImage" 
        :src="productImage" 
        :alt="product.name"
        class="product-img"
      />
      <div v-else class="image-placeholder">
        <div class="placeholder-icon">
          <span class="placeholder-svg">📱</span>
        </div>
        <span class="placeholder-text">{{ getFirstWord(product.name) }}</span>
      </div>
      
      <!-- Badges -->
      <div class="product-badges">
        <span v-if="isNew" class="badge badge--new">Novo</span>
        <span v-if="hasDiscount" class="badge badge--discount">-{{ calculateDiscountPercentage(originalPrice, product.price) }}%</span>
      </div>
      
      <!-- Botão de Favorito -->
      <button 
        @click="toggleFavorite"
        :class="['wishlist-btn-top', { 'is-favorite': isFavorite }]"
      >
        <span class="heart-icon">{{ isFavorite ? '❤️' : '🤍' }}</span>
      </button>
    </div>
    
    <!-- Informações do Produto -->
    <div class="product-info">
      <div class="product-header">
        <h3 class="product-name">{{ product.name }}</h3>
        <div class="product-rating">
          <div class="stars">
            <component
              v-for="star in 5"
              :key="star"
              :is="star <= productRating ? 'svg' : 'svg'"
              :class="['star', { filled: star <= productRating }]"
            >
              <path v-if="star <= productRating" fill="currentColor" d="M10.788 3.21c-.4-1.2-2.176-1.2-2.576 0L6.44 7.5H2.25c-1.264 0-1.791 1.621-.77 2.35l3.39 2.455-1.29 4.215c-.37 1.208 1.004 2.205 2.048 1.54L9.5 15.75l3.872 2.31c1.044.665 2.418-.332 2.048-1.54l-1.29-4.215 3.39-2.455c1.022-.729.494-2.35-.77-2.35H12.56l-1.772-4.29z"/>
              <path v-else fill="none" stroke="currentColor" stroke-width="1.5" d="M11.48 3.499a.75.75 0 0 0-1.415 0L8.318 7.5H4.11a.75.75 0 0 0-.44 1.356l3.39 2.455-1.29 4.215a.75.75 0 0 0 1.088.84L10 14.77l3.142 1.596a.75.75 0 0 0 1.088-.84l-1.29-4.215 3.39-2.455a.75.75 0 0 0-.44-1.356H11.682l-1.202-4.001z"/>
            </component>
          </div>
          <span class="rating-count">({{ ratingCount }})</span>
        </div>
      </div>
      
      <p class="description">{{ product.description }}</p>
      
      <div class="product-price">
        <span v-if="hasDiscount" class="original-price">
          {{ formatPrice(originalPrice) }}
        </span>
        <span class="current-price">{{ formatPrice(product.price) }}</span>
      </div>
      
      <div class="product-features">
        <span class="feature">🚚 Entrega grátis</span>
        <span class="feature">🛡️ Garantia 1 ano</span>
        <span class="feature">📱 {{ getCategoryDisplayName(product.category) }}</span>
      </div>
    </div>
    
    <!-- Ações do Produto -->
    <div class="product-actions">
      <button 
        @click="addToCart" 
        class="add-to-cart-btn"
        :disabled="isInCart"
      >
        <span class="cart-icon">🛒</span>
        {{ isInCart ? 'No Carrinho' : 'Adicionar ao Carrinho' }}
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useCartStore } from '@/stores/cart'
import { useSettingsStore } from '@/stores/settings'
import { useToastStore } from '@/stores/toast'
import { formatPrice, formatPriceWithDiscount, calculateDiscountPercentage } from '@/utils'
import { StarIcon } from '@heroicons/vue/24/outline'
import type { Product } from '@/types'

// Import das imagens
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

interface Props {
  product: Product
  viewMode?: 'grid' | 'list'
}

const props = withDefaults(defineProps<Props>(), {
  viewMode: 'grid'
})
const cartStore = useCartStore()
const settingsStore = useSettingsStore()
const toastStore = useToastStore()

const isInCart = computed(() => {
  return cartStore.items.some(item => item.product.id === props.product.id)
})

const addToCart = () => {
  cartStore.addItem(props.product)
  toastStore.success(
    'Produto Adicionado',
    `${props.product.name} foi adicionado ao carrinho!`
  )
}

const isFavorite = computed(() => {
  return settingsStore.isFavorite(props.product.id)
})

const toggleFavorite = () => {
  const action = settingsStore.toggleFavorite(props.product.id)
  if (action === 'added') {
    toastStore.success(
      'Favorito Adicionado',
      `${props.product.name} foi adicionado aos favoritos!`
    )
  } else {
    toastStore.info(
      'Favorito Removido',
      `${props.product.name} foi removido dos favoritos!`
    )
  }
}

// Usando formatPrice do utils

// Dados simulados para demonstração
const isNew = computed(() => props.product.id <= 2)
const hasDiscount = computed(() => props.product.id % 3 === 0)
const discountPercentage = computed(() => Math.floor(Math.random() * 20) + 10)
const originalPrice = computed(() => 
  hasDiscount.value ? props.product.price * (1 + discountPercentage.value / 100) : props.product.price
)

const productRating = computed(() => (props.product.id % 5) + 1)
const ratingCount = computed(() => Math.floor(Math.random() * 100) + 20)

const getFirstWord = (text: string): string => {
  return text.split(' ')[0]
}

const getCategoryDisplayName = (category: string): string => {
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
  
  return categoryMap[category] || category
}

// Mapeamento de imagens para produtos
const productImage = computed(() => {
  if (props.product.image) {
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
    return imageMap[props.product.image] || null
  }
  
  // Fallback para produtos sem imagem definida
  const productName = props.product.name.toLowerCase()
  
  if (productName.includes('notebook') || productName.includes('dell')) {
    return notebookDell
  }
  if (productName.includes('fone') || productName.includes('sony')) {
    return foneSony
  }
  if (productName.includes('samsung') || productName.includes('galaxy')) {
    return samsung
  }
  if (productName.includes('tv') || productName.includes('lg')) {
    return tvLg
  }
  if (productName.includes('ipad') || productName.includes('tablet')) {
    return ipad
  }
  if (productName.includes('iphone')) {
    return iphone
  }
  if (productName.includes('acer') || productName.includes('nitro')) {
    return acerNitro
  }
  if (productName.includes('gaming') && productName.includes('fone')) {
    return foneGaming
  }
  if (productName.includes('mouse')) {
    return mouse
  }
  if (productName.includes('pc gamer') || productName.includes('pichau')) {
    return pcGaming
  }
  
  return null
})
</script>

<style scoped>
.product-card {
  background: var(--product-card-bg);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: var(--product-card-shadow);
  transition: all 0.3s ease;
  border: 1px solid var(--product-card-border);
}

/* Garantir contraste em TODOS os elementos do card */
.product-card * {
  color: var(--text-primary);
}

.product-card h1, .product-card h2, .product-card h3, .product-card h4, .product-card h5, .product-card h6 {
  color: var(--text-primary);
}

.product-card p, .product-card span, .product-card div, .product-card label {
  color: var(--text-primary);
}

.product-card button {
  color: var(--text-primary);
}

.product-card input, .product-card select, .product-card textarea {
  color: var(--text-primary);
  background-color: var(--input-bg);
  border-color: var(--input-border);
}

.product-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Imagem do Produto */
.product-image {
  position: relative;
  height: 200px;
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.product-img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  object-position: center;
  transition: transform 0.3s ease;
  border-radius: 0;
  background: #f8fafc;
  padding: 16px;
}

.product-card:hover .product-img {
  transform: scale(1.05);
}

/* Ajuste para modo lista */
.product-card--list .product-img {
  border-radius: 12px 0 0 12px;
  padding: 12px;
}

.product-card--list .product-image {
  height: 150px;
}

.image-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 12px;
  width: 100%;
  height: 100%;
  padding: 16px;
}

.placeholder-icon {
  width: 80px;
  height: 80px;
  background: #e2e8f0;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.placeholder-svg {
  font-size: 40px;
  line-height: 1;
}

.placeholder-text {
  font-size: 14px;
  font-weight: 600;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Badges */
.product-badges {
  position: absolute;
  top: 12px;
  left: 12px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.badge {
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.badge--new {
  background: #10b981;
  color: white;
}

.badge--discount {
  background: #ef4444;
  color: white;
}

/* Botão de Favorito */
.wishlist-btn-top {
  position: absolute;
  top: 12px;
  right: 12px;
  background: white;
  border: none;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: all 0.2s ease;
}

.wishlist-btn-top:hover {
  background: #fef2f2;
  transform: scale(1.1);
}

.heart-icon {
  font-size: 20px;
  line-height: 1;
}

/* Informações do Produto */
.product-info {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 12px;
  min-height: 200px;
  justify-content: space-between;
}

.product-header {
  margin-bottom: 12px;
}

.product-name {
  font-size: 16px;
  font-weight: 700;
  color: #1f2937;
  margin: 0 0 8px 0;
  line-height: 1.3;
  min-height: 42px;
  display: flex;
  align-items: flex-start;
}

.product-rating {
  display: flex;
  align-items: center;
  gap: 8px;
}

.stars {
  display: flex;
  gap: 2px;
}

.star { width: 16px; height: 16px; color: #9ca3af; transition: color 0.2s ease; }
.star.filled { color: #fbbf24; fill: #fbbf24; }

.rating-count {
  font-size: 12px;
  color: #6b7280;
}

.description {
  font-size: 14px;
  color: #6b7280;
  margin: 0 0 16px 0;
  line-height: 1.4;
}

/* Preços */
.product-price {
  margin-bottom: 16px;
  min-height: 60px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 4px;
}

.original-price {
  display: block;
  font-size: 14px;
  color: #9ca3af;
  text-decoration: line-through;
  margin-bottom: 4px;
}

.current-price {
  font-size: 20px;
  font-weight: 900;
  color: #1f2937;
}

/* Features */
.product-features {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 20px;
  min-height: 70px;
  justify-content: center;
}

.feature {
  font-size: 12px;
  color: #6b7280;
}

/* Ações */
.product-actions {
  padding: 0 20px 20px;
  min-height: 60px;
  display: flex;
  align-items: center;
}

.add-to-cart-btn {
  width: 100%;
  padding: 14px;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 12px;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.add-to-cart-btn:hover:not(:disabled) {
  background: #2563eb;
  transform: translateY(-2px);
}

.add-to-cart-btn:disabled {
  background: #9ca3af;
  cursor: not-allowed;
  transform: none;
}

.cart-icon {
  font-size: 18px;
  line-height: 1;
}

/* Modo Lista */
.product-card--list {
  display: grid;
  grid-template-columns: 200px 1fr auto;
  gap: 24px;
  align-items: center;
}

.product-card--list .product-image {
  height: 150px;
}

.product-card--list .product-info {
  padding: 0;
  min-height: 150px;
  justify-content: space-between;
}

.product-card--list .product-actions {
  padding: 0;
  width: 200px;
}

@media (max-width: 768px) {
  .product-card--list {
    grid-template-columns: 1fr;
    gap: 16px;
  }
  
  .product-card--list .product-image {
    height: 200px;
  }
  
  .product-card--list .product-actions {
    width: 100%;
  }
}
</style>
