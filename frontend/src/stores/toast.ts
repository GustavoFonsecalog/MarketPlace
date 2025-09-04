import { defineStore } from 'pinia'
import { ref } from 'vue'

export interface Toast {
  id: string
  type: 'success' | 'warning' | 'error' | 'info'
  title: string
  message: string
  duration?: number
}

export const useToastStore = defineStore('toast', () => {
  const toasts = ref<Toast[]>([])

  const addToast = (toast: Omit<Toast, 'id'>) => {
    const id = `toast-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`
    const newToast: Toast = {
      id,
      duration: 5000, // 5 segundos por padrão
      ...toast
    }

    toasts.value.push(newToast)

    // Auto-remove após duração
    if (newToast.duration && newToast.duration > 0) {
      setTimeout(() => {
        removeToast(id)
      }, newToast.duration)
    }
  }

  const removeToast = (id: string) => {
    const index = toasts.value.findIndex(toast => toast.id === id)
    if (index > -1) {
      toasts.value.splice(index, 1)
    }
  }

  const clearToasts = () => {
    toasts.value = []
  }

  // Métodos de conveniência com durações específicas
  const success = (title: string, message: string, duration?: number) => {
    addToast({ type: 'success', title, message, duration: duration || 4000 })
  }

  const warning = (title: string, message: string, duration?: number) => {
    addToast({ type: 'warning', title, message, duration: duration || 6000 })
  }

  const error = (title: string, message: string, duration?: number) => {
    addToast({ type: 'error', title, message, duration: duration || 8000 })
  }

  const info = (title: string, message: string, duration?: number) => {
    addToast({ type: 'info', title, message, duration: duration || 5000 })
  }

  return {
    toasts,
    addToast,
    removeToast,
    clearToasts,
    success,
    warning,
    error,
    info
  }
})
