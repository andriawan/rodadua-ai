import { defineStore } from 'pinia'
import { ref } from 'vue'

export interface Modal {
  isOpen: boolean
  title: string
  message?: string
  onConfirm?: () => void
  onCancel?: () => void
}

export const useUiStore = defineStore('ui', () => {
  const loadingStates = ref<Record<string, boolean>>({})
  const notifications = ref<Array<{
    id: string
    type: 'success' | 'error' | 'warning' | 'info'
    message: string
    timeout?: number
  }>>([])
  const modal = ref<Modal>({
    isOpen: false,
    title: '',
  })

  function setLoading(key: string, isLoading: boolean) {
    loadingStates.value[key] = isLoading
  }

  function isLoading(key: string): boolean {
    return loadingStates.value[key] ?? false
  }

  function notify(type: 'success' | 'error' | 'warning' | 'info', message: string, timeout = 3000) {
    const id = Date.now().toString()
    notifications.value.push({ id, type, message, timeout })
    
    if (timeout > 0) {
      setTimeout(() => {
        dismissNotification(id)
      }, timeout)
    }
  }

  function dismissNotification(id: string) {
    const index = notifications.value.findIndex(n => n.id === id)
    if (index >= 0) {
      notifications.value.splice(index, 1)
    }
  }

  function openModal(title: string, message?: string, onConfirm?: () => void, onCancel?: () => void) {
    modal.value = {
      isOpen: true,
      title,
      message,
      onConfirm,
      onCancel,
    }
  }

  function closeModal() {
    modal.value.isOpen = false
  }

  return {
    loadingStates,
    notifications,
    modal,
    setLoading,
    isLoading,
    notify,
    dismissNotification,
    openModal,
    closeModal,
  }
})
