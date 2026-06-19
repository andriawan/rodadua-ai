import { ref } from 'vue'
import TroubleshootingService from '../services/TroubleshootingService'
import type { TroubleshootingEntry, CreateTroubleshootingInput } from '../types/troubleshooting'
import { useUiStore } from '../stores/uiStore'

export function useTroubleshooting() {
  const uiStore = useUiStore()
  const entries = ref<TroubleshootingEntry[]>([])
  const loading = ref(false)
  const analyzing = ref(false)
  const error = ref<string | null>(null)

  async function fetchForMotorcycle(motorcycleId: number, page = 1) {
    loading.value = true
    error.value = null
    try {
      const result = await TroubleshootingService.getForMotorcycle(motorcycleId, page)
      entries.value = result.data
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to fetch troubleshooting history'
      uiStore.notify('error', error.value)
    } finally {
      loading.value = false
    }
  }

  async function analyze(motorcycleId: number, data: CreateTroubleshootingInput) {
    analyzing.value = true
    error.value = null
    try {
      const entry = await TroubleshootingService.analyze(motorcycleId, data)
      entries.value.unshift(entry)
      uiStore.notify('success', 'Analysis complete')
      return entry
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to analyze problem'
      uiStore.notify('error', error.value)
      throw err
    } finally {
      analyzing.value = false
    }
  }

  return {
    entries,
    loading,
    analyzing,
    error,
    fetchForMotorcycle,
    analyze,
  }
}
