<template>
  <Transition
    enter-active-class="transition ease-out duration-300"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition ease-in duration-200"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div v-if="isOpen" class="settings-overlay" @click="closeModal">
      <div class="settings-modal" @click.stop>
        
        <!-- Modal de Confirmação para Limpar Favoritos -->
        <ConfirmModal
          :is-open="showClearFavoritesConfirm"
          title="Limpar Favoritos"
          message="Tem certeza que deseja limpar todos os favoritos?"
          details="Esta ação não pode ser desfeita. Todos os produtos favoritos serão removidos permanentemente."
          confirm-text="Limpar Tudo"
          cancel-text="Cancelar"
          type="danger"
          :on-confirm="confirmClearFavorites"
          @close="showClearFavoritesConfirm = false"
        />
        
        <!-- Modal de Confirmação para Reset de Configurações -->
        <ConfirmModal
          :is-open="showResetSettingsConfirm"
          title="Restaurar Configurações"
          message="Tem certeza que deseja restaurar as configurações padrão?"
          details="Todas as suas configurações personalizadas serão perdidas e restauradas para os valores padrão."
          confirm-text="Restaurar"
          cancel-text="Cancelar"
          type="warning"
          :on-confirm="confirmResetSettings"
          @close="showResetSettingsConfirm = false"
        />
        <!-- Header -->
        <div class="modal-header">
          <h2 class="modal-title">Configurações</h2>
          <button @click="closeModal" class="close-btn">
            <XMarkIcon class="close-icon" />
          </button>
        </div>

        <!-- Conteúdo -->
        <div class="modal-content">
          <!-- Tema -->
          <div class="setting-group">
            <h3 class="setting-title">Aparência</h3>
            <div class="setting-item">
              <label class="setting-label">Tema</label>
              <div class="theme-options">
                <button
                  @click="applyTheme('light')"
                  :class="['theme-btn', { active: settings.theme === 'light' }]"
                >
                  <SunIcon class="theme-icon" />
                  Claro
                </button>
                <button
                  @click="applyTheme('dark')"
                  :class="['theme-btn', { active: settings.theme === 'dark' }]"
                >
                  <MoonIcon class="theme-icon" />
                  Escuro
                </button>
              </div>
            </div>
          </div>

          <!-- Favoritos -->
          <div class="setting-group">
            <h3 class="setting-title">Favoritos</h3>
            <div class="setting-item">
              <label class="setting-label">Total de Favoritos</label>
              <span class="favorites-count">{{ favoritesCount() }}</span>
            </div>
            <div class="setting-item">
              <button @click="clearFavorites" class="clear-favorites-btn">
                Limpar Todos os Favoritos
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { useSettingsStore } from '@/stores/settings'
import { useToastStore } from '@/stores/toast'
import {
  XMarkIcon,
  SunIcon,
  MoonIcon
} from '@heroicons/vue/24/outline'
import ConfirmModal from './ConfirmModal.vue'

interface Props {
  isOpen: boolean
}

const props = defineProps<Props>()
const emit = defineEmits<{
  close: []
}>()

const settingsStore = useSettingsStore()
const toastStore = useToastStore()

const settings = computed(() => settingsStore.settings)

const closeModal = () => {
  emit('close')
}

const applyTheme = (theme: 'light' | 'dark') => {
  settingsStore.applyTheme(theme)
  toastStore.success(
    'Tema Alterado',
    `Tema ${theme === 'light' ? 'claro' : 'escuro'} aplicado com sucesso!`
  )
}

// Estados para controlar os modais de confirmação
const showClearFavoritesConfirm = ref(false)
const showResetSettingsConfirm = ref(false)

const clearFavorites = () => {
  showClearFavoritesConfirm.value = true
}

const resetSettings = () => {
  showResetSettingsConfirm.value = true
}

const confirmClearFavorites = () => {
  // Implementar limpeza de favoritos
  settingsStore.clearAllFavorites()
  toastStore.success(
    'Favoritos Limpos',
    'Todos os favoritos foram removidos com sucesso!'
  )
}

const confirmResetSettings = () => {
  // Implementar reset das configurações
  settingsStore.resetToDefaults()
  toastStore.success(
    'Configurações Restauradas',
    'As configurações foram restauradas para os valores padrão!'
  )
}

const favoritesCount = () => settingsStore.favoritesCount()
</script>

<style scoped>
.settings-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: var(--modal-overlay);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  padding: 20px;
}

.settings-modal {
  background: var(--modal-bg);
  border-radius: 16px;
  width: 100%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: var(--modal-shadow);
}

/* Garantir contraste em TODOS os elementos do modal */
.settings-modal * {
  color: var(--text-primary);
}

.settings-modal h1, .settings-modal h2, .settings-modal h3, .settings-modal h4, .settings-modal h5, .settings-modal h6 {
  color: var(--text-primary);
}

.settings-modal p, .settings-modal span, .settings-modal div, .settings-modal label {
  color: var(--text-primary);
}

.settings-modal button {
  color: var(--text-primary);
}

.settings-modal input, .settings-modal select, .settings-modal textarea {
  color: var(--text-primary);
  background-color: var(--input-bg);
  border-color: var(--input-border);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 24px 24px 0 24px;
  border-bottom: 1px solid var(--border-primary);
  padding-bottom: 20px;
}

.modal-title {
  font-size: 24px;
  font-weight: 700;
  color: var(--text-primary);
  margin: 0;
}

.close-btn {
  background: none;
  border: none;
  color: var(--text-tertiary);
  cursor: pointer;
  padding: 8px;
  border-radius: 8px;
  transition: all 0.2s ease;
}

.close-btn:hover {
  background: var(--bg-tertiary);
  color: var(--text-primary);
}

.close-icon {
  width: 24px;
  height: 24px;
}

.modal-content {
  padding: 24px;
}

.setting-group {
  margin-bottom: 32px;
}

.setting-group:last-child {
  margin-bottom: 0;
}

.setting-title {
  font-size: 18px;
  font-weight: 600;
  color: var(--text-primary);
  margin: 0 0 16px 0;
  padding-bottom: 8px;
  border-bottom: 1px solid var(--border-primary);
}

.setting-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 0;
  border-bottom: 1px solid var(--border-primary);
}

.setting-item:last-child {
  border-bottom: none;
}

.setting-label {
  font-size: 14px;
  font-weight: 500;
  color: var(--text-primary);
}

.setting-select {
  padding: 8px 12px;
  border: 1px solid var(--input-border);
  border-radius: 8px;
  font-size: 14px;
  background: var(--input-bg);
  color: var(--text-primary);
  min-width: 150px;
}

/* Tema */
.theme-options {
  display: flex;
  gap: 12px;
}

.theme-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 16px;
  border: 2px solid var(--border-primary);
  border-radius: 10px;
  background: var(--btn-secondary-bg);
  color: var(--btn-secondary-text);
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

.theme-btn:hover {
  border-color: var(--border-secondary);
  background: var(--btn-secondary-hover);
}

.theme-btn.active {
  border-color: var(--primary-color);
  background: var(--primary-color);
  color: var(--text-inverse);
  box-shadow: 0 0 0 2px var(--primary-color);
  transform: scale(1.05);
}

.theme-icon {
  width: 18px;
  height: 18px;
}

/* Toggle Switch */
.toggle-switch {
  position: relative;
  width: 50px;
  height: 24px;
}

.toggle-input {
  opacity: 0;
  width: 0;
  height: 0;
}

.toggle-label {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: var(--border-secondary);
  transition: 0.3s;
  border-radius: 24px;
}

.toggle-label:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: var(--bg-primary);
  transition: 0.3s;
  border-radius: 50%;
}

.toggle-input:checked + .toggle-label {
  background-color: var(--primary-color);
}

.toggle-input:checked + .toggle-label:before {
  transform: translateX(26px);
}

/* Favoritos */
.favorites-count {
  background: var(--primary-color);
  color: var(--text-inverse);
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 600;
}

.clear-favorites-btn {
  background: var(--error-color);
  color: var(--text-inverse);
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

.clear-favorites-btn:hover {
  background: var(--error-color);
  filter: brightness(0.9);
}

/* Footer */
.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  padding: 20px 24px 24px 24px;
  border-top: 1px solid var(--border-primary);
}

.btn-primary,
.btn-secondary {
  padding: 12px 24px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  border: none;
}

.btn-primary {
  background: var(--btn-primary-bg);
  color: var(--btn-primary-text);
}

.btn-primary:hover {
  background: var(--btn-primary-hover);
}

.btn-secondary {
  background: var(--btn-secondary-bg);
  color: var(--btn-secondary-text);
  border: 1px solid var(--border-secondary);
}

.btn-secondary:hover {
  background: var(--btn-secondary-hover);
}

/* CSS já está usando variáveis de tema, não precisa de override */

/* CSS já está usando variáveis de tema */

@media (max-width: 640px) {
  .settings-modal {
    max-width: 100%;
    margin: 20px;
  }
  
  .modal-content {
    padding: 20px;
  }
  
  .setting-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }
  
  .theme-options {
    flex-direction: column;
    width: 100%;
  }
  
  .theme-btn {
    justify-content: center;
  }
}
</style>
