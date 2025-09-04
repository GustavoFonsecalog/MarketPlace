<template>
  <section class="products-section">
    <div class="container">
      <!-- Header da Seção -->
      <div class="section-header">
        <div class="title-section">
          <h2 class="section-title">Produtos em Destaque</h2>
          <div v-if="selectedCategory" class="category-indicator">
            <span class="category-badge">{{ getCategoryName(selectedCategory) }}</span>
            <button @click="clearCategory" class="clear-category-btn">
              Remover Filtros
            </button>
          </div>
        </div>
        <div class="section-actions">
          <button 
            @click="toggleFilters"
            class="filter-toggle-btn"
          >
            <FunnelIcon class="filter-icon" />
            Filtros
          </button>
          <div class="view-options">
            <button 
              @click="setViewMode('grid')"
              :class="['view-btn', { active: viewMode === 'grid' }]"
            >
              <Squares2X2Icon class="view-icon" />
            </button>
            <button 
              @click="setViewMode('list')"
              :class="['view-btn', { active: viewMode === 'list' }]"
            >
              <ListBulletIcon class="view-icon" />
            </button>
          </div>
        </div>
      </div>

      <!-- Filtros Avançados -->
      <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 -translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-4"
      >
        <div v-if="showFilters" class="filters-panel">
          <div class="filters-grid">
            

            <!-- Filtro de Preço -->
            <div class="filter-group">
              <label class="filter-label">Faixa de Preço</label>
              <div class="price-range">
                <input 
                  v-model="filters.minPrice" 
                  type="number" 
                  placeholder="Min"
                  class="price-input"
                />
                <span class="price-separator">-</span>
                <input 
                  v-model="filters.maxPrice" 
                  type="number" 
                  placeholder="Max"
                  class="price-input"
                />
              </div>
            </div>

            

            <!-- Filtro de Avaliação -->
            <div class="filter-group">
              <label class="filter-label">Avaliação Mínima</label>
              <div class="rating-filter">
                <div 
                  v-for="rating in 5" 
                  :key="rating"
                  @click="filters.minRating = rating"
                  :class="['star', { active: filters.minRating >= rating }]"
                >
                  <StarIcon class="star-icon" />
                </div>
              </div>
            </div>

            
          </div>

          <!-- Ações dos Filtros -->
          <div class="filter-actions">
            <button @click="clearFilters" class="clear-filters-btn">
              Limpar Filtros
            </button>
            <button @click="applyFilters" class="apply-filters-btn">
              Aplicar Filtros
            </button>
          </div>
        </div>
      </Transition>

      <!-- Ordenação e Resultados -->
      <div class="results-header">
        <div class="results-info">
          <span class="results-count">{{ filteredProducts.length }} produtos encontrados</span>
        </div>
        <div class="sort-options">
          <label class="sort-label">Ordenar por:</label>
          <select v-model="sortBy" class="sort-select">
            <option value="relevance">Relevância</option>
            <option value="price-asc">Menor Preço</option>
            <option value="price-desc">Maior Preço</option>
            <option value="rating">Melhor Avaliação</option>
            <option value="newest">Mais Recentes</option>
          </select>
        </div>
      </div>

      <!-- Grid de Produtos -->
      <div :class="['products-grid', `products-grid--${viewMode}`]">
        <ProductCard 
          v-for="product in paginatedProducts" 
          :key="product.id" 
          :product="product"
          :view-mode="viewMode"
        />
      </div>

      <!-- Paginação -->
      <div v-if="totalPages > 1" class="pagination">
        <button 
          @click="previousPage"
          :disabled="currentPage === 1"
          class="pagination-btn"
        >
          <ChevronLeftIcon class="pagination-icon" />
          Anterior
        </button>
        
        <div class="page-numbers">
          <button 
            v-for="page in visiblePages" 
            :key="page"
            @click="goToPage(page)"
            :class="['page-btn', { active: page === currentPage }]"
          >
            {{ page }}
          </button>
        </div>
        
        <button 
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="pagination-btn"
        >
          Próxima
          <ChevronRightIcon class="pagination-icon" />
        </button>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { 
  FunnelIcon, 
  Squares2X2Icon, 
  ListBulletIcon,
  StarIcon,
  ChevronLeftIcon,
  ChevronRightIcon
} from '@heroicons/vue/24/outline'
import ProductCard from '@/components/ProductCard.vue'
import type { Product } from '@/types'

// Props
interface Props {
  products: Product[]
  selectedCategory?: string | null
}

const props = defineProps<Props>()

// Emits
const emit = defineEmits<{
  clearCategory: []
}>()

// Estado local
const showFilters = ref(false)
const viewMode = ref<'grid' | 'list'>('grid')
const currentPage = ref(1)
const itemsPerPage = 12

// Filtros
const filters = ref({
  minPrice: '',
  maxPrice: '',
  minRating: 0
})

const brands = ['Samsung', 'Apple', 'Dell', 'Sony', 'LG', 'Asus', 'Lenovo']

// Métodos para categoria
const getCategoryName = (categoryId: string): string => {
  const categoryNames: Record<string, string> = {
    smartphones: 'Smartphones',
    laptops: 'Notebooks',
    headphones: 'Fones de Ouvido',
    tvs: 'Smart TVs',
    cameras: 'Câmeras',
    gaming: 'Gaming',
    tablets: 'Tablets'
  }
  return categoryNames[categoryId] || categoryId
}

const clearCategory = () => {
  emit('clearCategory')
}

// Computed
const filteredProducts = computed(() => {
  let filtered = [...props.products]

  // Filtro de preço
  if (filters.value.minPrice) {
    filtered = filtered.filter(product => 
      product.price >= Number(filters.value.minPrice)
    )
  }
  if (filters.value.maxPrice) {
    filtered = filtered.filter(product => 
      product.price <= Number(filters.value.maxPrice)
    )
  }

  // Filtro de avaliação (simulado)
  if (filters.value.minRating > 0) {
    filtered = filtered.filter(product => {
      // Simular avaliação baseada no ID do produto
      const rating = (product.id % 5) + 1
      return rating >= filters.value.minRating
    })
  }

  return filtered
})

const sortedProducts = computed(() => {
  const sorted = [...filteredProducts.value]
  
  switch (sortBy.value) {
    case 'price-asc':
      return sorted.sort((a, b) => a.price - b.price)
    case 'price-desc':
      return sorted.sort((a, b) => b.price - a.price)
    case 'rating':
      return sorted.sort((a, b) => (b.id % 5) - (a.id % 5))
    case 'newest':
      return sorted.sort((a, b) => b.id - a.id)
    default:
      return sorted
  }
})

const totalPages = computed(() => 
  Math.ceil(sortedProducts.value.length / itemsPerPage)
)

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return sortedProducts.value.slice(start, end)
})

const visiblePages = computed(() => {
  const pages = []
  const maxVisible = 5
  let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2))
  let end = Math.min(totalPages.value, start + maxVisible - 1)
  
  if (end - start + 1 < maxVisible) {
    start = Math.max(1, end - maxVisible + 1)
  }
  
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  
  return pages
})

// Estado de ordenação
const sortBy = ref('relevance')

// Métodos
const toggleFilters = () => {
  showFilters.value = !showFilters.value
}

const setViewMode = (mode: 'grid' | 'list') => {
  viewMode.value = mode
}

const clearFilters = () => {
  filters.value = {
    minPrice: '',
    maxPrice: '',
    minRating: 0
  }
}

const applyFilters = () => {
  currentPage.value = 1
  showFilters.value = false
}

const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
  }
}

const goToPage = (page: number) => {
  currentPage.value = page
}

// Watchers
watch(filters, () => {
  currentPage.value = 1
}, { deep: true })

watch(sortBy, () => {
  currentPage.value = 1
})
</script>

<style scoped>
.products-section {
  background: var(--bg-secondary);
  padding: 80px 0;
  border-top: 1px solid var(--border-primary);
  box-shadow: inset 0 4px 12px rgba(0, 0, 0, 0.05);
  position: relative;
}

.products-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: linear-gradient(90deg, transparent 0%, var(--primary-color) 50%, transparent 100%);
  opacity: 0.6;
}

/* Garantir contraste em TODOS os elementos da seção de produtos */
.products-section * {
  color: var(--text-primary);
}

.products-section h1, .products-section h2, .products-section h3, .products-section h4, .products-section h5, .products-section h6 {
  color: var(--text-primary);
}

.products-section p, .products-section span, .products-section div, .products-section label {
  color: var(--text-primary);
}

.products-section button {
  color: var(--text-primary);
}

.products-section input, .products-section select, .products-section textarea {
  color: var(--text-primary);
  background-color: var(--input-bg);
  border-color: var(--input-border);
}

.container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 24px;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 40px;
}

.title-section {
  display: flex;
  align-items: center;
  gap: 16px;
  flex-wrap: wrap;
}

.category-indicator {
  display: flex;
  align-items: center;
  gap: 12px;
}

.category-badge {
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
  color: var(--text-inverse);
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 600;
}

.clear-category-btn {
  background: var(--text-secondary);
  color: var(--text-inverse);
  border: none;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.clear-category-btn:hover {
  background: var(--text-primary);
  transform: translateY(-1px);
  box-shadow: var(--shadow-md);
}

.section-title {
  font-size: 36px;
  font-weight: 900;
  color: var(--text-primary);
  margin: 0;
}

.section-actions {
  display: flex;
  align-items: center;
  gap: 16px;
}

.filter-toggle-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 20px;
  background: var(--filter-bg);
  border: 1px solid var(--filter-border);
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  color: var(--text-primary);
  cursor: pointer;
  transition: all 0.2s ease;
}

.filter-toggle-btn:hover {
  background: var(--bg-tertiary);
  border-color: var(--border-secondary);
}

.filter-icon {
  width: 20px;
  height: 20px;
}

.view-options {
  display: flex;
  border: 1px solid var(--filter-border);
  border-radius: 8px;
  overflow: hidden;
}

.view-btn {
  padding: 12px;
  background: var(--filter-bg);
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  color: var(--text-primary);
}

.view-btn.active {
  background: var(--primary-color);
  color: var(--text-inverse);
}

.view-btn:not(.active):hover {
  background: var(--bg-tertiary);
}

.view-icon {
  width: 20px;
  height: 20px;
}

/* Filtros */
.filters-panel {
  background: var(--filter-bg);
  border: 1px solid var(--filter-border);
  border-radius: 16px;
  padding: 32px;
  margin-bottom: 32px;
  box-shadow: var(--filter-shadow);
}

.filters-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 24px;
}

.filter-group {
  display: flex;
  flex-direction: column;
}

.filter-label {
  font-size: 14px;
  font-weight: 600;
  color: var(--text-primary);
  margin-bottom: 8px;
}

.filter-select {
  padding: 12px;
  border: 1px solid var(--input-border);
  border-radius: 8px;
  font-size: 14px;
  background: var(--input-bg);
  color: var(--text-primary);
}

.price-range {
  display: flex;
  align-items: center;
  gap: 8px;
}

.price-input {
  flex: 1;
  padding: 12px;
  border: 1px solid var(--input-border);
  border-radius: 8px;
  font-size: 14px;
  background: var(--input-bg);
  color: var(--text-primary);
}

.price-separator {
  color: var(--text-secondary);
  font-weight: 600;
}

.brand-filters {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.brand-checkbox {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  font-size: 14px;
  color: var(--text-primary);
}

.brand-checkbox input[type="checkbox"] {
  display: none;
}

.checkmark {
  width: 18px;
  height: 18px;
  border: 2px solid #d1d5db;
  border-radius: 4px;
  position: relative;
  transition: all 0.2s ease;
}

.brand-checkbox input[type="checkbox"]:checked + .checkmark {
  background: #3b82f6;
  border-color: #3b82f6;
}

.brand-checkbox input[type="checkbox"]:checked + .checkmark::after {
  content: '✓';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white;
  font-size: 12px;
  font-weight: bold;
}

.rating-filter {
  display: flex;
  gap: 4px;
}

.star {
  cursor: pointer;
  transition: all 0.2s ease;
}

.star.active .star-icon { color: #fbbf24; fill: #fbbf24; }

.star-icon { width: 24px; height: 24px; color: #6b7280; transition: all 0.2s ease; }

.availability-filters {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.availability-checkbox {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  font-size: 14px;
  color: #374151;
}

.filter-actions {
  display: flex;
  justify-content: flex-end;
  gap: 16px;
  margin-top: 24px;
  padding-top: 24px;
  border-top: 1px solid #e5e7eb;
}

.clear-filters-btn {
  padding: 12px 24px;
  background: white;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  color: #6b7280;
  cursor: pointer;
  transition: all 0.2s ease;
}

.clear-filters-btn:hover {
  background: #f3f4f6;
  border-color: #9ca3af;
}

.apply-filters-btn {
  padding: 12px 24px;
  background: #3b82f6;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  color: white;
  cursor: pointer;
  transition: all 0.2s ease;
}

.apply-filters-btn:hover {
  background: #2563eb;
}

/* Resultados */
.results-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 32px;
}

.results-count {
  font-size: 16px;
  color: #6b7280;
}

.sort-options {
  display: flex;
  align-items: center;
  gap: 12px;
}

.sort-label {
  font-size: 14px;
  color: #374151;
  font-weight: 600;
}

.sort-select {
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 14px;
  background: white;
}

/* Grid de Produtos */
.products-grid {
  display: grid;
  gap: 24px;
  margin-bottom: 48px;
}

.products-grid--grid {
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
}

.products-grid--list {
  grid-template-columns: 1fr;
}

/* Paginação */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 16px;
}

.pagination-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 20px;
  background: var(--filter-bg);
  border: 1px solid var(--filter-border);
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  color: var(--text-primary);
  cursor: pointer;
  transition: all 0.2s ease;
}

.pagination-btn:hover:not(:disabled) {
  background: var(--bg-tertiary);
  border-color: var(--border-secondary);
}

.pagination-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination-icon {
  width: 20px;
  height: 20px;
}

.page-numbers {
  display: flex;
  gap: 8px;
}

.page-btn {
  width: 40px;
  height: 40px;
  background: var(--filter-bg);
  border: 1px solid var(--filter-border);
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  color: var(--text-primary);
  cursor: pointer;
  transition: all 0.2s ease;
}

.page-btn:hover {
  background: var(--bg-tertiary);
  border-color: var(--border-secondary);
}

.page-btn.active {
  background: var(--primary-color);
  color: var(--text-inverse);
  border-color: var(--primary-color);
}

@media (max-width: 1024px) {
  .filters-grid {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  }
  
  .section-header {
    flex-direction: column;
    gap: 24px;
    align-items: flex-start;
  }
}

@media (max-width: 768px) {
  .products-section {
    padding: 40px 0;
  }
  
  .container {
    padding: 0 16px;
  }
  
  .section-title {
    font-size: 28px;
  }
  
  .filters-panel {
    padding: 24px;
  }
  
  .filters-grid {
    grid-template-columns: 1fr;
  }
  
  .results-header {
    flex-direction: column;
    gap: 16px;
    align-items: flex-start;
  }
  
  .products-grid--grid {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  }
}
</style>
