import { ref } from 'vue'
import MaintenanceService from '../services/MaintenanceService'
import type { Maintenance, MaintenanceRecommendation } from '../types/maintenance'
import { useUiStore } from '../stores/uiStore'

export function useMaintenance() {
  const uiStore = useUiStore()
  const maintenance = ref<Maintenance[]>([])
  const recommendations = ref<MaintenanceRecommendation[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  async function fetchForMotorcycle(motorcycleId: number, page = 1) {
    loading.value = true
    error.value = null
    try {
      const result = await MaintenanceService.getForMotorcycle(motorcycleId, page)
      maintenance.value = result.data
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to fetch maintenance'
      uiStore.notify('error', error.value)
    } finally {
      loading.value = false
    }
  }

  async function fetchRecommendations(motorcycleId: number) {
    loading.value = true
    error.value = null
    try {
      recommendations.value = await MaintenanceService.getRecommendations(motorcycleId)
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to fetch recommendations'
    } finally {
      loading.value = false
    }
  }

  async function addMaintenance(motorcycleId: number, data: Partial<Maintenance>) {
    loading.value = true
    error.value = null
    try {
      const newRecord = await MaintenanceService.create(motorcycleId, data)
      maintenance.value.unshift(newRecord)
      uiStore.notify('success', 'Maintenance record added')
      return newRecord
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to add maintenance'
      uiStore.notify('error', error.value)
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    maintenance,
    recommendations,
    loading,
    error,
    fetchForMotorcycle,
    fetchRecommendations,
    addMaintenance,
  }
}
