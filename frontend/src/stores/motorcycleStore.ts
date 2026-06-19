import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { Motorcycle } from '../types/motorcycle'
import MotorcycleService from '../services/MotorcycleService'

export const useMotorcycleStore = defineStore('motorcycle', () => {
  const motorcycles = ref<Motorcycle[]>([])
  const currentMotorcycle = ref<Motorcycle | null>(null)
  const favorites = ref<number[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)
  const pagination = ref({ page: 1, total: 0, per_page: 15, last_page: 1 })

  const allFavorites = computed(() => 
    motorcycles.value.filter(m => m.is_favorite)
  )

  async function fetchAll(page = 1) {
    loading.value = true
    error.value = null
    try {
      const result = await MotorcycleService.getAll(page)
      motorcycles.value = result.data
      pagination.value = {
        page: result.current_page,
        total: result.total,
        per_page: result.per_page,
        last_page: result.last_page,
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to fetch motorcycles'
    } finally {
      loading.value = false
    }
  }

  async function fetchById(id: number) {
    loading.value = true
    error.value = null
    try {
      currentMotorcycle.value = await MotorcycleService.getById(id)
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to fetch motorcycle'
    } finally {
      loading.value = false
    }
  }

  async function create(data: any) {
    loading.value = true
    error.value = null
    try {
      const newMoto = await MotorcycleService.create(data)
      motorcycles.value.unshift(newMoto)
      return newMoto
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to create motorcycle'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function update(id: number, data: any) {
    loading.value = true
    error.value = null
    try {
      const updated = await MotorcycleService.update(id, data)
      const index = motorcycles.value.findIndex(m => m.id === id)
      if (index >= 0) {
        motorcycles.value[index] = updated
      }
      if (currentMotorcycle.value?.id === id) {
        currentMotorcycle.value = updated
      }
      return updated
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to update motorcycle'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function delete_motorcycle(id: number) {
    loading.value = true
    error.value = null
    try {
      await MotorcycleService.delete(id)
      motorcycles.value = motorcycles.value.filter(m => m.id !== id)
      if (currentMotorcycle.value?.id === id) {
        currentMotorcycle.value = null
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to delete motorcycle'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function toggleFavorite(id: number) {
    try {
      const updated = await MotorcycleService.toggleFavorite(id)
      const index = motorcycles.value.findIndex(m => m.id === id)
      if (index >= 0) {
        motorcycles.value[index] = updated
      }
      if (currentMotorcycle.value?.id === id) {
        currentMotorcycle.value = updated
      }
    } catch (err) {
      console.error('Failed to toggle favorite:', err)
    }
  }

  return {
    motorcycles,
    currentMotorcycle,
    favorites,
    allFavorites,
    loading,
    error,
    pagination,
    fetchAll,
    fetchById,
    create,
    update,
    delete_motorcycle,
    toggleFavorite,
  }
})
