<template>
  <TransitionGroup
    name="toast"
    tag="div"
    class="toast-container"
  >
    <div
      v-for="toast in toasts"
      :key="toast.id"
      :class="['toast', `toast--${toast.type}`]"
    >
      <div class="toast-icon">
        <CheckCircleIcon v-if="toast.type === 'success'" class="icon" />
        <ExclamationTriangleIcon v-else-if="toast.type === 'warning'" class="icon" />
        <XCircleIcon v-else-if="toast.type === 'error'" class="icon" />
        <InformationCircleIcon v-else class="icon" />
      </div>
      <div class="toast-content">
        <h4 class="toast-title">{{ toast.title }}</h4>
        <p class="toast-message">{{ toast.message }}</p>
        <div class="toast-progress">
          <div class="progress-bar" :style="{ animationDuration: `${toast.duration}ms` }"></div>
        </div>
      </div>
      <button @click="removeToast(toast.id)" class="toast-close">
        <XMarkIcon class="close-icon" />
      </button>
    </div>
  </TransitionGroup>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useToastStore } from '@/stores/toast'
import {
  CheckCircleIcon,
  ExclamationTriangleIcon,
  XCircleIcon,
  InformationCircleIcon,
  XMarkIcon
} from '@heroicons/vue/24/outline'

const toastStore = useToastStore()

const toasts = computed(() => toastStore.toasts)

const removeToast = (id: string) => {
  toastStore.removeToast(id)
}
</script>

<style scoped>
.toast-container {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  gap: 12px;
  max-width: 400px;
}

.toast {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 16px;
  border-radius: 12px;
  box-shadow: var(--toast-shadow);
  background: var(--toast-bg);
  border-left: 4px solid;
  min-width: 300px;
}

/* Garantir contraste em TODOS os elementos do toast */
.toast * {
  color: var(--text-primary);
}

.toast h1, .toast h2, .toast h3, .toast h4, .toast h5, .toast h6 {
  color: var(--text-primary);
}

.toast p, .toast span, .toast div, .toast label {
  color: var(--text-primary);
}

.toast button {
  color: var(--text-primary);
}

.toast input, .toast select, .toast textarea {
  color: var(--text-primary);
  background-color: var(--input-bg);
  border-color: var(--input-border);
}

.toast--success {
  border-left-color: #10b981;
}

.toast--success .progress-bar {
  background: #10b981;
}

.toast--warning {
  border-left-color: #f59e0b;
}

.toast--warning .progress-bar {
  background: #f59e0b;
}

.toast--error {
  border-left-color: #ef4444;
}

.toast--error .progress-bar {
  background: #ef4444;
}

.toast--info {
  border-left-color: #3b82f6;
}

.toast--info .progress-bar {
  background: #3b82f6;
}

.toast-icon {
  flex-shrink: 0;
  margin-top: 2px;
}

.toast--success .icon {
  color: #10b981;
}

.toast--warning .icon {
  color: #f59e0b;
}

.toast--error .icon {
  color: #ef4444;
}

.toast--info .icon {
  color: #3b82f6;
}

.icon {
  width: 20px;
  height: 20px;
}

.toast-content {
  flex: 1;
  min-width: 0;
}

.toast-title {
  font-size: 14px;
  font-weight: 600;
  color: var(--text-primary);
  margin: 0 0 4px 0;
}

.toast-message {
  font-size: 13px;
  color: var(--text-secondary);
  margin: 0 0 8px 0;
  line-height: 1.4;
}

.toast-progress {
  width: 100%;
  height: 2px;
  background: #e5e7eb;
  border-radius: 1px;
  overflow: hidden;
}

.progress-bar {
  height: 100%;
  background: currentColor;
  border-radius: 1px;
  animation: progress-shrink linear forwards;
}

@keyframes progress-shrink {
  from {
    width: 100%;
  }
  to {
    width: 0%;
  }
}

.toast-close {
  background: none;
  border: none;
  color: #9ca3af;
  cursor: pointer;
  padding: 4px;
  border-radius: 6px;
  transition: all 0.2s ease;
  flex-shrink: 0;
}

.toast-close:hover {
  background: #f3f4f6;
  color: #6b7280;
}

.close-icon {
  width: 16px;
  height: 16px;
}

/* Animações */
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

.toast-move {
  transition: transform 0.3s ease;
}

/* Dark mode */
:global(.dark) .toast {
  background: #1f2937;
  border-color: #374151;
}

:global(.dark) .toast-title {
  color: #f9fafb;
}

:global(.dark) .toast-message {
  color: #d1d5db;
}

:global(.dark) .toast-close:hover {
  background: #374151;
  color: #9ca3af;
}

:global(.dark) .toast-progress {
  background: #374151;
}
</style>
