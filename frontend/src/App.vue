<template>
  <div id="app">
    <!-- Navegação Principal -->
    <Navigation 
      :selected-category="selectedCategory"
      @toggle-cart="toggleCart" 
      @category-selected="filterByCategory"
    />
    
    <!-- Conteúdo Principal -->
    <main class="main">
      <!-- Hero Section com Carrossel -->
      <HeroSection />
      
      <!-- Seção de Produtos com Filtros -->
      <ProductsSection 
        :products="filteredProducts" 
        :selected-category="selectedCategory"
        @clear-category="clearCategory"
      />
      
      <!-- Carrinho Sidebar -->
      <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 translate-x-full"
        enter-to-class="opacity-100 translate-x-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-x-0"
        leave-to-class="opacity-0 translate-x-full"
      >
        <div v-if="isCartOpen" class="cart-overlay" @click="closeCart">
          <div class="cart-sidebar" @click.stop>
            <Cart />
            <button @click="closeCart" class="close-cart-btn">
              <XMarkIcon class="close-icon" />
            </button>
          </div>
        </div>
      </Transition>
    </main>

    <!-- Modal de Configurações -->
    <SettingsModal 
      :is-open="settingsStore.isSettingsOpen"
      @close="settingsStore.toggleSettings"
    />

    <!-- Sistema de Toasts -->
    <Toast />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { useCartStore } from '@/stores/cart'
import { useSettingsStore } from '@/stores/settings'
import Navigation from '@/components/layout/Navigation.vue'
import HeroSection from '@/components/sections/HeroSection.vue'
import ProductsSection from '@/components/sections/ProductsSection.vue'
import Cart from '@/components/Cart.vue'
import SettingsModal from '@/components/modals/SettingsModal.vue'
import Toast from '@/components/ui/Toast.vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import type { Product } from '@/types'

const cartStore = useCartStore()
const settingsStore = useSettingsStore()
const isCartOpen = ref(false)

const products: Product[] = [
  // Smartphones
  {
    id: 1,
    name: 'Smartphone Galaxy S23',
    price: 2999.99,
    description: 'Smartphone Samsung Galaxy S23 128GB com câmera de 108MP',
    category: 'smartphones',
    image: 'samsung'
  },
  {
    id: 6,
    name: 'iPhone 15 Pro',
    price: 8999.99,
    description: 'iPhone 15 Pro 256GB com Dynamic Island e câmera tripla',
    category: 'smartphones',
    image: 'iphone'
  },
  
  // Notebooks
  {
    id: 2,
    name: 'Notebook Dell Inspiron',
    price: 4599.99,
    description: 'Notebook Dell Inspiron 15" Intel i5 8GB RAM 512GB SSD',
    category: 'laptops',
    image: 'notebook-dell'
  },
  {
    id: 7,
    name: 'Acer Nitro 5 Gaming',
    price: 6999.99,
    description: 'Notebook Gamer Acer Nitro 5 Intel i7 RTX 4060 16GB RAM',
    category: 'laptops',
    image: 'acer-nitro'
  },
  
  // Fones de Ouvido
  {
    id: 3,
    name: 'Fone de Ouvido Sony',
    price: 299.99,
    description: 'Fone de ouvido sem fio Sony WH-1000XM4 com cancelamento de ruído',
    category: 'headphones',
    image: 'fone-sone'
  },
  {
    id: 8,
    name: 'Headset Gaming RGB',
    price: 599.99,
    description: 'Headset Gaming com microfone boom e iluminação RGB personalizável',
    category: 'headphones',
    image: 'fone-gaming'
  },
  
  // Smart TVs
  {
    id: 4,
    name: 'Smart TV LG 55"',
    price: 3499.99,
    description: 'Smart TV LG 55" 4K UHD com webOS e HDR',
    category: 'tvs',
    image: 'tv-lg'
  },
  
  // Tablets
  {
    id: 5,
    name: 'Tablet iPad Air',
    price: 5999.99,
    description: 'Tablet Apple iPad Air 10.9" 64GB com chip M1',
    category: 'tablets',
    image: 'ipad'
  },
  
  // Gaming
  {
    id: 9,
    name: 'Mouse Gaming RGB',
    price: 399.99,
    description: 'Mouse Gaming com sensor óptico 25K DPI e iluminação RGB',
    category: 'gaming',
    image: 'mouse'
  },
  {
    id: 10,
    name: 'PC Gamer PICHAU',
    price: 12999.99,
    description: 'PC Gamer completo Intel i7 RTX 4070 32GB RAM 1TB NVMe',
    category: 'gaming',
    image: 'pc-gaming'
  },
  
  // Câmeras (sem imagens por enquanto)
  {
    id: 11,
    name: 'Câmera DSLR Canon',
    price: 3999.99,
    description: 'Câmera DSLR Canon EOS 2000D com lente 18-55mm',
    category: 'cameras'
  },
  {
    id: 12,
    name: 'Câmera Action GoPro',
    price: 2499.99,
    description: 'Câmera Action GoPro Hero 11 Black 5.3K com estabilização',
    category: 'cameras'
  }
]

const selectedCategory = ref<string | null>(null)
const filteredProducts = ref<Product[]>(products)

const filterByCategory = (categoryId: string) => {
  selectedCategory.value = categoryId
  
  if (categoryId === 'all') {
    filteredProducts.value = products
  } else if (categoryId === 'deals') {
    // Produtos com desconto (IDs que são múltiplos de 3)
    filteredProducts.value = products.filter(product => product.id % 3 === 0)
  } else if (categoryId === 'clearance') {
    // Produtos em liquidação (preços mais baixos)
    filteredProducts.value = products.filter(product => product.price < 1000)
  } else if (categoryId === 'new') {
    // Produtos novos (IDs 1 e 2)
    filteredProducts.value = products.filter(product => product.id <= 2)
  } else {
    filteredProducts.value = products.filter(product => {
      // Mapeamento de categorias para produtos
      const categoryMap: Record<string, string[]> = {
        smartphones: ['smartphones', 'smartphone', 'galaxy', 'iphone', 'samsung'],
        laptops: ['laptops', 'notebook', 'dell', 'inspiron', 'acer', 'nitro'],
        headphones: ['headphones', 'fone', 'sony', 'ouvido', 'headset', 'gaming'],
        tvs: ['tvs', 'tv', 'lg', 'smart tv'],
        tablets: ['tablets', 'tablet', 'ipad'],
        gaming: ['gaming', 'game', 'jogo', 'mouse', 'pc gamer', 'pichau'],
        cameras: ['cameras', 'câmera', 'foto', 'canon', 'gopro', 'dslr', 'action']
      }
      
      const searchTerms = categoryMap[categoryId] || []
      return searchTerms.some(term => 
        product.name.toLowerCase().includes(term.toLowerCase()) ||
        product.description.toLowerCase().includes(term.toLowerCase()) ||
        product.category === categoryId
      )
    })
  }
  
  // Scroll suave para a seção de produtos
  setTimeout(() => {
    const productsSection = document.querySelector('.products-section')
    if (productsSection) {
      productsSection.scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
      })
    }
  }, 50)
}

const toggleCart = () => {
  isCartOpen.value = !isCartOpen.value
}

const closeCart = () => {
  isCartOpen.value = false
}

const clearCategory = () => {
  selectedCategory.value = null
  filteredProducts.value = products
}

// Ouvir evento simples para fechar o carrinho após finalizar
const handleCloseCart = () => { isCartOpen.value = false }
onMounted(() => window.addEventListener('close-cart', handleCloseCart))
onUnmounted(() => window.removeEventListener('close-cart', handleCloseCart))
</script>

<style scoped>
#app {
  min-height: 100vh;
  background: var(--bg-secondary);
  color: var(--text-primary);
}

/* Garantir contraste em TODOS os elementos da aplicação */
#app * {
  color: var(--text-primary);
}

/* Elementos específicos que precisam de contraste */
#app h1, #app h2, #app h3, #app h4, #app h5, #app h6 {
  color: var(--text-primary);
}

#app p, #app span, #app div, #app label {
  color: var(--text-primary);
}

#app button {
  color: var(--text-primary);
}

#app input, #app select, #app textarea {
  color: var(--text-primary);
  background-color: var(--input-bg);
  border-color: var(--input-border);
}

.main {
  position: relative;
}

/* Carrinho Sidebar */
.cart-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: var(--cart-overlay);
  z-index: 1000;
  display: flex;
  justify-content: flex-end;
}

.cart-sidebar {
  width: 100%;
  max-width: 500px;
  height: 100vh;
  background: var(--cart-bg);
  overflow-y: auto;
  position: relative;
  display: flex;
  flex-direction: column;
}

.close-cart-btn {
  position: absolute;
  top: 20px;
  right: 20px;
  background: #ef4444;
  color: white;
  border: none;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
  z-index: 10;
}

.close-cart-btn:hover {
  background: #dc2626;
  transform: scale(1.1);
}

.close-icon {
  width: 20px;
  height: 20px;
}

@media (max-width: 768px) {
  .cart-sidebar {
    max-width: 100%;
    width: 100%;
  }
  
  .cart-overlay {
    justify-content: center;
  }
}
</style>
