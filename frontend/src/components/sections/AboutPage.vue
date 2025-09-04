<template>
  <section class="about">
    <h2>Sobre o Sistema</h2>
    <p>
      Este projeto implementa um módulo completo de carrinho de compras com API PHP (Slim) e
      frontend em Vue 3 + Pinia. O usuário pode adicionar produtos, escolher a forma de
      pagamento (PIX, Cartão à vista ou Parcelado 2–12x) e visualizar o valor final com
      descontos ou juros aplicados conforme as regras:
    </p>
    <ul>
      <li>PIX: 10% de desconto</li>
      <li>Cartão de Crédito (1x): 10% de desconto</li>
      <li>Parcelado (2–12x): Juros compostos de 1% a.m. (M = P·(1+0,01)^n)</li>
    </ul>
    <p>
      O backend aplica arredondamento bancário em todas as etapas e possui testes automatizados
      garantindo os cálculos. A UI exibe os totais antes de finalizar e registra o pedido no
      histórico local.
    </p>
  </section>
</template>

<script setup lang="ts">
</script>

<style scoped>
.about { padding: 24px; line-height: 1.6; }
.about h2 { margin: 0 0 12px 0; }
.about ul { margin: 12px 0; padding-left: 18px; }
</style>


