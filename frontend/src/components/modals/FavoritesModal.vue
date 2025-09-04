<template>
  <div v-if="isOpen" class="modal-overlay" @click.self="close">
    <div class="modal">
      <div class="modal-header">
        <h3>Favoritos</h3>
        <button class="close-btn" @click="close">×</button>
      </div>
      <div class="modal-content" v-if="favorites.length">
        <div class="fav-item" v-for="product in favorites" :key="product.id">
          <img :src="getImage(product)" :alt="product.name" />
          <div class="info">
            <h4>{{ product.name }}</h4>
            <span>{{ formatPrice(product.price) }}</span>
          </div>
          <button class="add-btn" @click="add(product)">Adicionar</button>
        </div>
      </div>
      <div v-else class="empty">Nenhum favorito ainda.</div>
    </div>
  </div>
  
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useSettingsStore } from '@/stores/settings'
import { useCartStore } from '@/stores/cart'
import { formatPrice } from '@/utils'
import type { Product } from '@/types'

const props = defineProps<{ isOpen: boolean }>()
const emit = defineEmits<{ 'close': [] }>()

const settings = useSettingsStore()
const cart = useCartStore()

const favorites = computed<Product[]>(() => settings.getFavorites())

const close = () => emit('close')
const add = (p: Product) => cart.addItem(p)

const getImage = (p: Product) => {
  return p.image ? `/src/assets/${p.image}.png` : ''
}
</script>

<style scoped>
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.5); display:flex; align-items:center; justify-content:center; z-index: 50; }
.modal { width: 520px; max-width: 95vw; background: var(--card-bg); border:1px solid var(--border-primary); border-radius:12px; box-shadow: var(--shadow-xl); }
.modal-header { display:flex; align-items:center; justify-content:space-between; padding:16px; border-bottom:1px solid var(--border-primary); }
.modal-content { max-height: 60vh; overflow:auto; padding: 12px 16px; display:flex; flex-direction:column; gap:12px; }
.fav-item { display:flex; gap:12px; align-items:center; border:1px solid var(--border-secondary); border-radius:8px; padding:8px; }
.fav-item img { width:56px; height:56px; object-fit:cover; border-radius:8px; background: var(--bg-tertiary); }
.fav-item .info { flex:1; display:flex; flex-direction:column; gap:4px; }
.add-btn, .close-btn { background: var(--primary-color); color: var(--text-inverse); border:none; border-radius:6px; padding:8px 12px; cursor:pointer; }
.close-btn { background: transparent; color: var(--text-primary); font-size: 20px; }
.empty { padding: 20px; text-align:center; color: var(--text-secondary); }
</style>


