import { ref } from 'vue'
import WorkshopService from '../services/WorkshopService'
import type { Workshop, WorkshopSearchFilters } from '../types/workshop'
import { useUiStore } from '../stores/uiStore'

export function useWorkshop() {
  const uiStore = useUiStore()
  const workshops = ref<Workshop[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  async function search(filters: WorkshopSearchFilters) {
    loading.value = true
    error.value = null
    try {
      workshops.value = await WorkshopService.search(filters)
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to search workshops'
      uiStore.notify('error', error.value)
    } finally {
      loading.value = false
    }
  }

  async function getById(id: number) {
    loading.value = true
    error.value = null
    try {
      return await WorkshopService.getById(id)
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to fetch workshop'
      uiStore.notify('error', error.value)
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    workshops,
    loading,
    error,
    search,
    getById,
  }
}
