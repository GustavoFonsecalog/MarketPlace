<template>
  <nav class="navigation">
    <div class="nav-container">
      <!-- Logo -->
      <div class="nav-logo">
        <a href="#" @click.prevent="goHome" class="logo-link">
          <span class="logo-text">MARKETPLACE</span>
        </a>
      </div>
      <!-- Modais/Seções auxiliares -->
      <FavoritesModal :is-open="isFavoritesOpen" @close="isFavoritesOpen = false" />
      <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="isOrdersOpen" class="orders-overlay" @click.self="isOrdersOpen = false">
          <div class="orders-sheet">
            <div class="orders-header">
              <h3>Meus Pedidos</h3>
              <button class="close" @click="isOrdersOpen = false">×</button>
            </div>
            <OrdersPage />
          </div>
        </div>
      </Transition>

      <!-- Menu Principal -->
      <div class="nav-menu">
        <div class="nav-item">
          <button 
            @click="toggleDropdown('new')"
            :class="['nav-button', { active: activeDropdown === 'new' }]"
          >
            NOVIDADES
            <ChevronDownIcon class="chevron-icon" />
          </button>
          <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-1"
          >
            <div v-if="activeDropdown === 'new'" class="dropdown-menu">
              <div class="dropdown-content">
                <div class="dropdown-section">
                  <h3>Lançamentos</h3>
                  <ul>
                    <li><a href="#" @click="navigateToCategory('smartphones')" :class="{ active: selectedCategory === 'smartphones' }">Smartphones</a></li>
                    <li><a href="#" @click="navigateToCategory('laptops')" :class="{ active: selectedCategory === 'laptops' }">Notebooks</a></li>
                    <li><a href="#" @click="navigateToCategory('gaming')" :class="{ active: selectedCategory === 'gaming' }">Gaming</a></li>
                    <li><a href="#" @click="navigateToCategory('cameras')" :class="{ active: selectedCategory === 'cameras' }">Câmeras</a></li>
                  </ul>
                </div>
                <div class="dropdown-section">
                  <h3>Ofertas Especiais</h3>
                  <ul>
                    <li><a href="#" @click="navigateToCategory('deals')" :class="{ active: selectedCategory === 'deals' }">Promoções</a></li>
                    <li><a href="#" @click="navigateToCategory('clearance')" :class="{ active: selectedCategory === 'clearance' }">Liquidação</a></li>
                    <li><a href="#" @click="navigateToCategory('new')" :class="{ active: selectedCategory === 'new' }">Novos Produtos</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </Transition>
        </div>

        <div class="nav-item">
          <button 
            @click="toggleDropdown('categories')"
            :class="['nav-button', { active: activeDropdown === 'categories' }]"
          >
            CATEGORIAS
            <ChevronDownIcon class="chevron-icon" />
          </button>
          <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-1"
          >
            <div v-if="activeDropdown === 'categories'" class="dropdown-menu">
              <div class="dropdown-content">
                <div class="dropdown-section">
                  <h3>Eletrônicos</h3>
                  <ul>
                    <li><a href="#" @click="navigateToCategory('smartphones')" :class="{ active: selectedCategory === 'smartphones' }">Smartphones</a></li>
                    <li><a href="#" @click="navigateToCategory('laptops')" :class="{ active: selectedCategory === 'laptops' }">Notebooks</a></li>
                    <li><a href="#" @click="navigateToCategory('tablets')" :class="{ active: selectedCategory === 'tablets' }">Tablets</a></li>
                    <li><a href="#" @click="navigateToCategory('tvs')" :class="{ active: selectedCategory === 'tvs' }">Smart TVs</a></li>
                  </ul>
                </div>
                <div class="dropdown-section">
                  <h3>Gaming & Áudio</h3>
                  <ul>
                    <li><a href="#" @click="navigateToCategory('gaming')" :class="{ active: selectedCategory === 'gaming' }">Gaming</a></li>
                    <li><a href="#" @click="navigateToCategory('headphones')" :class="{ active: selectedCategory === 'headphones' }">Fones de Ouvido</a></li>
                    <li><a href="#" @click="navigateToCategory('cameras')" :class="{ active: selectedCategory === 'cameras' }">Câmeras</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </Transition>
        </div>

        

        <div class="nav-item">
          <a href="#" @click.prevent="navigateToAbout" class="nav-link">SOBRE</a>
        </div>
      </div>

      <!-- Ações do Usuário -->
      <div class="nav-actions">


        <!-- Carrinho -->
        <button @click="toggleCart" class="cart-button">
          <ShoppingBagIcon class="cart-icon" />
          <span v-if="cartStore.totalItems > 0" class="cart-badge">
            {{ cartStore.totalItems }}
          </span>
        </button>

        <!-- Menu do Usuário -->
        <div class="user-menu">
          <button @click="toggleUserMenu" class="user-button">
            <UserIcon class="user-icon" />
          </button>
          <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-1"
          >
            <div v-if="isUserMenuOpen" class="user-dropdown">
              <a href="#" @click.prevent="navigateToOrders" class="user-dropdown-item">Meus Pedidos</a>
              <a href="#" @click.prevent="navigateToFavorites" class="user-dropdown-item">
                Favoritos
                <span v-if="favoritesCount > 0" class="favorites-badge">{{ favoritesCount }}</span>
              </a>
              <a href="#" @click.prevent="openSettings" class="user-dropdown-item">Configurações</a>
            </div>
          </Transition>
        </div>
      </div>
      <!-- Modais/Seções auxiliares -->
      <FavoritesModal :is-open="isFavoritesOpen" @close="isFavoritesOpen = false" />
      <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="isOrdersOpen" class="orders-overlay" @click.self="isOrdersOpen = false">
          <div class="orders-sheet">
            <div class="orders-header">
              <h3>Meus Pedidos</h3>
              <button class="close" @click="isOrdersOpen = false">×</button>
            </div>
            <OrdersPage />
          </div>
        </div>
      </Transition>
      <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="isAboutOpen" class="orders-overlay" @click.self="isAboutOpen = false">
          <div class="orders-sheet">
            <div class="orders-header">
              <h3>Sobre</h3>
              <button class="close" @click="isAboutOpen = false">×</button>
            </div>
            <AboutPage />
          </div>
        </div>
      </Transition>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useCartStore } from '@/stores/cart'
import { useSettingsStore } from '@/stores/settings'
import FavoritesModal from '@/components/modals/FavoritesModal.vue'
import OrdersPage from '@/components/sections/OrdersPage.vue'
import AboutPage from '@/components/sections/AboutPage.vue'
// Toast store removido - não é mais necessário
import { 
  ChevronDownIcon, 
  MagnifyingGlassIcon, 
  ShoppingBagIcon, 
  UserIcon 
} from '@heroicons/vue/24/outline'

interface Props {
  selectedCategory?: string | null
}

const props = withDefaults(defineProps<Props>(), {
  selectedCategory: null
})

const cartStore = useCartStore()
const settingsStore = useSettingsStore()
// Toast store removido - não é mais necessário

const activeDropdown = ref<string | null>(null)
const isUserMenuOpen = ref(false)
const isFavoritesOpen = ref(false)
const isOrdersOpen = ref(false)
const isAboutOpen = ref(false)
const searchQuery = ref('')

const favoritesCount = computed(() => settingsStore.favoritesCount())

const toggleDropdown = (dropdown: string) => {
  if (activeDropdown.value === dropdown) {
    activeDropdown.value = null
  } else {
    activeDropdown.value = dropdown
  }
}

const toggleUserMenu = () => {
  isUserMenuOpen.value = !isUserMenuOpen.value
}

const toggleCart = () => {
  // Emitir evento para abrir o carrinho
  emit('toggle-cart')
}

const navigateToCategory = (category: string) => {
  console.log('Navegando para categoria:', category)
  activeDropdown.value = null
  
  // Emitir evento para o componente pai
  emit('category-selected', category)
  
  // Fechar dropdown
  setTimeout(() => {
    activeDropdown.value = null
  }, 100)
}

const goHome = () => {
  console.log('Navegando para home')
  // Implementar navegação para home
}



const navigateToAbout = () => {
  isAboutOpen.value = true
}

const navigateToAccount = () => {
  toastStore.info('Minha Conta', 'Funcionalidade em desenvolvimento')
  isUserMenuOpen.value = false
}

const navigateToOrders = () => {
  isOrdersOpen.value = true
  isUserMenuOpen.value = false
}

const navigateToFavorites = () => {
  isFavoritesOpen.value = true
  isUserMenuOpen.value = false
}

const openSettings = () => {
  settingsStore.toggleSettings()
  isUserMenuOpen.value = false
}

const logout = () => {
  toastStore.info('Logout', 'Funcionalidade em desenvolvimento')
  isUserMenuOpen.value = false
}

const handleSearch = () => {
  // Implementar busca
  console.log('Buscando:', searchQuery.value)
}

// Fechar dropdowns ao clicar fora
const handleClickOutside = (event: Event) => {
  const target = event.target as HTMLElement
  if (!target.closest('.nav-item') && !target.closest('.user-menu')) {
    activeDropdown.value = null
    isUserMenuOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})

const emit = defineEmits<{
  'toggle-cart': []
  'category-selected': [category: string]
}>()
</script>

<style scoped>
.navigation {
  background: var(--nav-bg);
  border-bottom: 1px solid var(--nav-border);
  position: sticky;
  top: 0;
  z-index: 50;
  box-shadow: var(--nav-shadow);
}

/* Garantir contraste em TODOS os elementos da navegação */
.navigation * {
  color: var(--text-primary);
}

.navigation h1, .navigation h2, .navigation h3, .navigation h4, .navigation h5, .navigation h6 {
  color: var(--text-primary);
}

.navigation p, .navigation span, .navigation div, .navigation label {
  color: var(--text-primary);
}

.navigation button {
  color: var(--text-primary);
}

.navigation input, .navigation select, .navigation textarea {
  color: var(--text-primary);
  background-color: var(--input-bg);
  border-color: var(--input-border);
}

.nav-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 24px;
  display: flex;
  align-items: center;
  height: 72px;
}

.nav-logo {
  margin-right: 48px;
}

.logo-link {
  text-decoration: none;
}

.logo-text {
  font-size: 24px;
  font-weight: 900;
  color: var(--text-primary);
  letter-spacing: -0.5px;
}

.nav-menu {
  display: flex;
  align-items: center;
  gap: 8px;
  flex: 1;
}

.nav-item {
  position: relative;
}

.nav-button {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 12px 16px;
  background: none;
  border: none;
  font-size: 14px;
  font-weight: 600;
  color: var(--text-primary);
  cursor: pointer;
  border-radius: 8px;
  transition: all 0.2s ease;
}

.nav-button:hover {
  background: var(--bg-tertiary);
  color: var(--text-primary);
}

.nav-button.active {
  background: var(--bg-tertiary);
  color: var(--text-primary);
}

.nav-link {
  padding: 12px 16px;
  text-decoration: none;
  font-size: 14px;
  font-weight: 600;
  color: var(--text-primary);
  border-radius: 8px;
  transition: all 0.2s ease;
}

.nav-link:hover {
  background: var(--bg-tertiary);
  color: var(--text-primary);
}

.chevron-icon {
  width: 16px;
  height: 16px;
  transition: transform 0.2s ease;
}

.nav-button.active .chevron-icon {
  transform: rotate(180deg);
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  background: var(--dropdown-bg);
  border: 1px solid var(--dropdown-border);
  border-radius: 12px;
  box-shadow: var(--dropdown-shadow);
  min-width: 400px;
  margin-top: 8px;
}

.dropdown-content {
  padding: 24px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 32px;
}

.dropdown-section h3 {
  font-size: 14px;
  font-weight: 700;
  color: var(--text-primary);
  margin: 0 0 16px 0;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.dropdown-section ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.dropdown-section li {
  margin-bottom: 8px;
}

.dropdown-section a {
  color: var(--text-secondary);
  text-decoration: none;
  font-size: 14px;
  transition: all 0.2s ease;
  padding: 8px 12px;
  border-radius: 6px;
  display: block;
  cursor: pointer;
}

.dropdown-section a:hover {
  color: var(--text-primary);
  background: var(--bg-tertiary);
  transform: translateX(4px);
}

.dropdown-section a.active {
  color: var(--text-inverse);
  background: var(--primary-color);
  font-weight: 600;
  box-shadow: var(--shadow-sm);
}

.nav-actions {
  display: flex;
  align-items: center;
  gap: 16px;
}

.search-container {
  position: relative;
}

.search-input {
  width: 240px;
  padding: 12px 16px 12px 44px;
  border: 1px solid var(--input-border);
  border-radius: 24px;
  font-size: 14px;
  background: var(--input-bg);
  color: var(--text-primary);
  transition: all 0.2s ease;
}

.search-input:focus {
  outline: none;
  border-color: var(--border-focus);
  background: var(--input-bg);
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.search-icon {
  position: absolute;
  left: 16px;
  top: 50%;
  transform: translateY(-50%);
  width: 20px;
  height: 20px;
  color: var(--text-tertiary);
}

.cart-button {
  position: relative;
  background: none;
  border: none;
  padding: 12px;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.cart-button:hover {
  background: var(--bg-tertiary);
}

.cart-icon {
  width: 24px;
  height: 24px;
  color: var(--text-primary);
}

.cart-badge {
  position: absolute;
  top: 4px;
  right: 4px;
  background: var(--error-color);
  color: var(--text-inverse);
  font-size: 12px;
  font-weight: 700;
  padding: 2px 6px;
  border-radius: 12px;
  min-width: 20px;
  text-align: center;
}

.user-menu {
  position: relative;
}

.user-button {
  background: none;
  border: none;
  padding: 12px;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.user-button:hover {
  background: var(--bg-tertiary);
}

.user-icon {
  width: 24px;
  height: 24px;
  color: var(--text-primary);
}

.user-dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  background: var(--dropdown-bg);
  border: 1px solid var(--dropdown-border);
  border-radius: 12px;
  box-shadow: var(--dropdown-shadow);
  min-width: 200px;
  margin-top: 8px;
  padding: 8px 0;
}

.orders-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.4); display:flex; justify-content:center; align-items:center; z-index: 40; }
.orders-sheet { width: 720px; max-width: 95vw; background: var(--card-bg); border:1px solid var(--border-primary); border-radius: 12px; box-shadow: var(--shadow-xl); }
.orders-header { display:flex; justify-content:space-between; align-items:center; padding: 12px 16px; border-bottom: 1px solid var(--border-primary); }
.orders-header .close { background: transparent; border:none; color: var(--text-primary); font-size: 20px; cursor: pointer; }

.user-dropdown-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 16px;
  color: var(--text-primary);
  text-decoration: none;
  font-size: 14px;
  transition: background-color 0.2s ease;
}

.favorites-badge {
  background: var(--primary-color);
  color: var(--text-inverse);
  font-size: 11px;
  font-weight: 600;
  padding: 2px 6px;
  border-radius: 10px;
  min-width: 18px;
  text-align: center;
}

.user-dropdown-item:hover {
  background: var(--bg-tertiary);
}

.user-dropdown-divider {
  border: none;
  border-top: 1px solid var(--border-primary);
  margin: 8px 0;
}

@media (max-width: 1024px) {
  .nav-menu {
    display: none;
  }
  
  .search-input {
    width: 180px;
  }
}

@media (max-width: 768px) {
  .nav-container {
    padding: 0 16px;
  }
  
  .search-container {
    display: none;
  }
  
  .nav-logo {
    margin-right: 24px;
  }
}
</style>
