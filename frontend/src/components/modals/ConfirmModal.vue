<template>
  <Transition
    enter-active-class="transition ease-out duration-300"
    enter-from-class="opacity-0 scale-95"
    enter-to-class="opacity-100 scale-100"
    leave-active-class="transition ease-in duration-200"
    leave-from-class="opacity-100 scale-100"
    leave-to-class="opacity-0 scale-95"
  >
    <div v-if="isOpen" class="confirm-overlay" @click="handleOverlayClick">
      <div class="confirm-modal" @click.stop>
        <!-- Header -->
        <div class="modal-header">
          <div class="header-icon">
            <ExclamationTriangleIcon class="warning-icon" />
          </div>
          <h3 class="modal-title">{{ title }}</h3>
          <button @click="handleCancel" class="close-btn">
            <XMarkIcon class="close-icon" />
          </button>
        </div>

        <!-- Conteúdo -->
        <div class="modal-content">
          <p class="confirm-message">{{ message }}</p>
          
          <!-- Detalhes adicionais se fornecidos -->
          <div v-if="details" class="confirm-details">
            <p class="details-text">{{ details }}</p>
          </div>
        </div>

        <!-- Ações -->
        <div class="modal-actions">
          <button 
            @click="handleCancel" 
            class="btn-secondary"
          >
            {{ cancelText }}
          </button>
          <button 
            @click="handleConfirm" 
            class="btn-primary"
            :class="{ 'btn-danger': type === 'danger' }"
          >
            {{ confirmText }}
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { 
  ExclamationTriangleIcon, 
  XMarkIcon 
} from '@heroicons/vue/24/outline'

interface Props {
  isOpen: boolean
  title?: string
  message: string
  details?: string
  confirmText?: string
  cancelText?: string
  type?: 'default' | 'danger' | 'warning'
  onConfirm: () => void
  onCancel?: () => void
}

const props = withDefaults(defineProps<Props>(), {
  title: 'Confirmação',
  confirmText: 'Confirmar',
  cancelText: 'Cancelar',
  type: 'default'
})

const emit = defineEmits<{
  close: []
}>()

const handleConfirm = () => {
  props.onConfirm()
  emit('close')
}

const handleCancel = () => {
  if (props.onCancel) {
    props.onCancel()
  }
  emit('close')
}

const handleOverlayClick = () => {
  emit('close')
}

// Classes CSS baseadas no tipo
const modalClasses = computed(() => ({
  'confirm-modal': true,
  [`confirm-modal--${props.type}`]: true
}))
</script>

<style scoped>
.confirm-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: var(--modal-overlay);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10000;
  padding: 20px;
}

.confirm-modal {
  background: var(--modal-bg);
  border-radius: 16px;
  width: 100%;
  max-width: 480px;
  box-shadow: var(--modal-shadow);
  overflow: hidden;
}

/* Header */
.modal-header {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 24px 24px 0 24px;
  border-bottom: 1px solid var(--border-primary);
  padding-bottom: 20px;
}

.header-icon {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: var(--bg-tertiary);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.warning-icon {
  width: 24px;
  height: 24px;
  color: var(--warning-color);
}

.modal-title {
  font-size: 20px;
  font-weight: 700;
  color: var(--text-primary);
  margin: 0;
  flex: 1;
}

.close-btn {
  background: none;
  border: none;
  color: var(--text-tertiary);
  cursor: pointer;
  padding: 8px;
  border-radius: 8px;
  transition: all 0.2s ease;
  flex-shrink: 0;
}

.close-btn:hover {
  background: var(--bg-tertiary);
  color: var(--text-primary);
}

.close-icon {
  width: 20px;
  height: 20px;
}

/* Conteúdo */
.modal-content {
  padding: 24px;
}

.confirm-message {
  font-size: 16px;
  color: var(--text-primary);
  margin: 0 0 16px 0;
  line-height: 1.5;
}

.confirm-details {
  background: var(--bg-tertiary);
  border-radius: 8px;
  padding: 16px;
  margin-top: 16px;
}

.details-text {
  font-size: 14px;
  color: var(--text-secondary);
  margin: 0;
  line-height: 1.4;
}

/* Ações */
.modal-actions {
  display: flex;
  gap: 12px;
  padding: 0 24px 24px 24px;
  justify-content: flex-end;
}

.btn-secondary {
  padding: 12px 24px;
  background: var(--btn-secondary-bg);
  color: var(--btn-secondary-text);
  border: 1px solid var(--border-secondary);
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-secondary:hover {
  background: var(--btn-secondary-hover);
  border-color: var(--border-primary);
}

.btn-primary {
  padding: 12px 24px;
  background: var(--btn-primary-bg);
  color: var(--btn-primary-text);
  border: 1px solid var(--btn-primary-bg);
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-primary:hover {
  background: var(--btn-primary-hover);
  border-color: var(--btn-primary-hover);
}

.btn-danger {
  background: var(--error-color);
  border-color: var(--error-color);
}

.btn-danger:hover {
  background: var(--error-color);
  filter: brightness(0.9);
  border-color: var(--error-color);
}

/* Responsividade */
@media (max-width: 640px) {
  .confirm-modal {
    max-width: 100%;
    margin: 0 16px;
  }
  
  .modal-actions {
    flex-direction: column;
  }
  
  .btn-secondary,
  .btn-primary {
    width: 100%;
    text-align: center;
  }
}
</style>
