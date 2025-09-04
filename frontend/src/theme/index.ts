/**
 * Sistema de Temas
 * 
 * Este arquivo exporta os temas disponíveis e funções de gerenciamento
 */

// Importar estilos CSS
import './light.css'
import './dark.css'
import './globals.css'
import './contrast.css'

// Tipos de tema disponíveis
export type Theme = 'light' | 'dark'

// Interface para configuração de tema
export interface ThemeConfig {
  name: Theme
  label: string
  icon: string
  description: string
}

// Configurações dos temas
export const themes: ThemeConfig[] = [
  {
    name: 'light',
    label: 'Claro',
    icon: '☀️',
    description: 'Tema claro para uso diurno'
  },
  {
    name: 'dark',
    label: 'Escuro',
    icon: '🌙',
    description: 'Tema escuro para uso noturno'
  }
]

// Função para aplicar tema
export const applyTheme = (theme: Theme): void => {
  const root = document.documentElement
  
  // Remover classes de tema anteriores
  root.classList.remove('light', 'dark')
  
  // Adicionar nova classe de tema
  root.classList.add(theme)
  
  // Salvar no localStorage
  localStorage.setItem('theme', theme)
  
  // Atualizar meta theme-color para mobile
  const metaThemeColor = document.querySelector('meta[name="theme-color"]')
  if (metaThemeColor) {
    metaThemeColor.setAttribute('content', theme === 'dark' ? '#111827' : '#ffffff')
  }
}

// Função para obter tema atual
export const getCurrentTheme = (): Theme => {
  // Verificar localStorage primeiro
  const savedTheme = localStorage.getItem('theme') as Theme
  
  if (savedTheme && ['light', 'dark'].includes(savedTheme)) {
    return savedTheme
  }
  
  // Verificar preferência do sistema
  if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    return 'dark'
  }
  
  // Padrão: tema claro
  return 'light'
}

// Função para alternar tema
export const toggleTheme = (): Theme => {
  const currentTheme = getCurrentTheme()
  const newTheme: Theme = currentTheme === 'light' ? 'dark' : 'light'
  
  applyTheme(newTheme)
  return newTheme
}

// Função para inicializar tema
export const initializeTheme = (): void => {
  const theme = getCurrentTheme()
  applyTheme(theme)
  
  // Escutar mudanças na preferência do sistema
  if (window.matchMedia) {
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
    mediaQuery.addEventListener('change', (e) => {
      if (!localStorage.getItem('theme')) {
        // Só aplicar se o usuário não tiver escolhido manualmente
        applyTheme(e.matches ? 'dark' : 'light')
      }
    })
  }
}

// Função para obter tema oposto
export const getOppositeTheme = (theme: Theme): Theme => {
  return theme === 'light' ? 'dark' : 'light'
}

// Função para verificar se tema é escuro
export const isDarkTheme = (theme: Theme): boolean => {
  return theme === 'dark'
}

// Função para verificar se tema é claro
export const isLightTheme = (theme: Theme): boolean => {
  return theme === 'light'
}
