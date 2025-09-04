<template>
  <section class="orders">
    <h2>Meus Pedidos</h2>
    <div v-if="orders.length" class="list">
      <div class="order" v-for="order in orders" :key="order.id">
        <div class="row">
          <span class="label">Pedido</span><span>#{{ order.id }}</span>
        </div>
        <div class="row">
          <span class="label">Data</span><span>{{ new Date(order.date).toLocaleDateString('pt-BR') }}</span>
        </div>
        <div class="row">
          <span class="label">Itens</span><span>{{ order.items.length }}</span>
        </div>
        <div class="row">
          <span class="label">Pagamento</span><span>{{ order.installments }}x</span>
        </div>
        <div class="items">
          <div class="items-header">
            <span>Produto</span>
            <span>Qtd.</span>
            <span>Valor</span>
          </div>
          <div class="item" v-for="it in order.items" :key="`${order.id}-${it.productId}`">
            <span>{{ it.name }}</span>
            <span>x{{ it.quantity }}</span>
            <span>{{ formatPrice(it.price) }}</span>
          </div>
        </div>
        <div class="row total">
          <span class="label">Total</span><span>{{ formatPrice(order.total) }}</span>
        </div>
      </div>
    </div>
    <div v-else class="empty">Você ainda não finalizou compras.</div>
  </section>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { formatPrice } from '@/utils'

interface OrderItem { productId: number; name: string; quantity: number; price: number }
interface Order { id: string; date: string; items: OrderItem[]; total: number; installments: number }

const orders = computed<Order[]>(() => {
  // Simples simulação: lê do localStorage (onde salvaríamos após finalizar)
  const raw = localStorage.getItem('orders')
  return raw ? JSON.parse(raw) : []
})
</script>

<style scoped>
.orders { padding: 24px; }
.list { display:flex; flex-direction:column; gap:12px; }
.order { border:1px solid var(--border-primary); border-radius:12px; padding:12px; background: var(--card-bg); }
.row { display:flex; justify-content:space-between; padding:4px 0; }
.label { color: var(--text-secondary); }
.items { border-top:1px solid var(--border-secondary); margin-top:8px; padding-top:8px; display:flex; flex-direction:column; gap:6px; }
.items-header {
  display: grid;
  grid-template-columns: 1fr 64px 140px;
  column-gap: 12px;
  padding: 6px 0;
  color: var(--text-secondary);
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  border-bottom: 1px solid var(--border-secondary);
}
.items-header span:nth-child(2),
.items-header span:last-child { text-align: right; font-variant-numeric: tabular-nums; }
.item { 
  display: grid;
  grid-template-columns: 1fr 64px 140px;
  column-gap: 12px;
  align-items: center;
  font-size: 14px;
  color: var(--text-secondary);
  padding: 6px 0;
}
.item span:nth-child(2),
.item span:last-child { text-align: right; font-variant-numeric: tabular-nums; }
.total span:last-child { font-weight: 700; }
.empty { color: var(--text-secondary); }
</style>


