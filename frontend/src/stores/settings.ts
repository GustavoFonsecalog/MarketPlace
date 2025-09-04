import { defineStore } from 'pinia'
import { ref, watch } from 'vue'
import { applyTheme, getCurrentTheme, type Theme } from '@/theme'

export interface Settings {
  theme: 'light' | 'dark'
  language: 'pt-BR' | 'en-US'
  notifications: boolean
  autoSave: boolean
  compactMode: boolean
}

export interface Favorite {
  id: number
  productId: number
  addedAt: Date
}

export const useSettingsStore = defineStore('settings', () => {
  // Estado
  const settings = ref<Settings>({
    theme: 'light',
    language: 'pt-BR',
    notifications: true,
    autoSave: true,
    compactMode: false
  })

  const favorites = ref<Favorite[]>([])
  const isSettingsOpen = ref(false)

  // Carregar configurações do localStorage
  const loadSettings = () => {
    const savedSettings = localStorage.getItem('marketplace-settings')
    const savedFavorites = localStorage.getItem('marketplace-favorites')
    
    if (savedSettings) {
      settings.value = { ...settings.value, ...JSON.parse(savedSettings) }
    }
    
    if (savedFavorites) {
      favorites.value = JSON.parse(savedFavorites).map((fav: any) => ({
        ...fav,
        addedAt: new Date(fav.addedAt)
      }))
    }
  }

  // Salvar configurações no localStorage
  const saveSettings = () => {
    localStorage.setItem('marketplace-settings', JSON.stringify(settings.value))
  }

  // Salvar favoritos no localStorage
  const saveFavorites = () => {
    localStorage.setItem('marketplace-favorites', JSON.stringify(favorites.value))
  }

  // Aplicar tema
  const applyThemeLocal = (theme: 'light' | 'dark') => {
    settings.value.theme = theme
    applyTheme(theme)
    saveSettings()
  }

  // Alternar tema
  const toggleTheme = () => {
    const newTheme = settings.value.theme === 'light' ? 'dark' : 'light'
    applyThemeLocal(newTheme)
  }

  // Adicionar/remover favorito
  const toggleFavorite = (productId: number) => {
    const existingIndex = favorites.value.findIndex(fav => fav.productId === productId)
    
    if (existingIndex >= 0) {
      favorites.value.splice(existingIndex, 1)
    } else {
      favorites.value.push({
        id: Date.now(),
        productId,
        addedAt: new Date()
      })
    }
    
    saveFavorites()
    return existingIndex >= 0 ? 'removed' : 'added'
  }

  // Verificar se produto é favorito
  const isFavorite = (productId: number) => {
    return favorites.value.some(fav => fav.productId === productId)
  }

  // Contar favoritos
  const favoritesCount = () => favorites.value.length

  // Limpar todos os favoritos
  const clearAllFavorites = () => {
    favorites.value = []
    saveFavorites()
  }

  // Restaurar configurações padrão
  const resetToDefaults = () => {
    settings.value = {
      theme: 'light',
      language: 'pt-BR',
      notifications: true,
      autoSave: true,
      compactMode: false
    }
    saveSettings()
    applyTheme(settings.value.theme)
  }

  // Abrir/fechar configurações
  const toggleSettings = () => {
    isSettingsOpen.value = !isSettingsOpen.value
  }

  // Atualizar configuração
  const updateSetting = <K extends keyof Settings>(key: K, value: Settings[K]) => {
    settings.value[key] = value
    saveSettings()
  }

  // Carregar configurações na inicialização
  loadSettings()

  // Aplicar tema inicial
  if (typeof document !== 'undefined') {
    applyTheme(settings.value.theme)
  }

  // Salvar automaticamente quando mudanças ocorrem
  watch(settings, saveSettings, { deep: true })
  watch(favorites, saveFavorites, { deep: true })

  return {
    settings,
    favorites,
    isSettingsOpen,
    applyTheme: applyThemeLocal,
    toggleTheme,
    toggleFavorite,
    isFavorite,
    favoritesCount,
    clearAllFavorites,
    resetToDefaults,
    toggleSettings,
    updateSetting
  }
}, {
  persist: true
})
