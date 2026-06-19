import { useMotorcycleStore } from '../stores/motorcycleStore'
import { useUiStore } from '../stores/uiStore'

export function useMotorcycle() {
  const motorcycleStore = useMotorcycleStore()
  const uiStore = useUiStore()

  async function fetchAll(page = 1) {
    await motorcycleStore.fetchAll(page)
  }

  async function fetchById(id: number) {
    await motorcycleStore.fetchById(id)
  }

  async function create(data: any) {
    try {
      const moto = await motorcycleStore.create(data)
      uiStore.notify('success', 'Motorcycle added successfully')
      return moto
    } catch (err) {
      uiStore.notify('error', 'Failed to add motorcycle')
      throw err
    }
  }

  async function update(id: number, data: any) {
    try {
      const moto = await motorcycleStore.update(id, data)
      uiStore.notify('success', 'Motorcycle updated successfully')
      return moto
    } catch (err) {
      uiStore.notify('error', 'Failed to update motorcycle')
      throw err
    }
  }

  async function deleteMotorcycle(id: number) {
    try {
      await motorcycleStore.delete_motorcycle(id)
      uiStore.notify('success', 'Motorcycle deleted successfully')
    } catch (err) {
      uiStore.notify('error', 'Failed to delete motorcycle')
      throw err
    }
  }

  async function toggleFavorite(id: number) {
    await motorcycleStore.toggleFavorite(id)
  }

  return {
    motorcycles: motorcycleStore.motorcycles,
    currentMotorcycle: motorcycleStore.currentMotorcycle,
    loading: motorcycleStore.loading,
    error: motorcycleStore.error,
    pagination: motorcycleStore.pagination,
    fetchAll,
    fetchById,
    create,
    update,
    deleteMotorcycle,
    toggleFavorite,
  }
}
