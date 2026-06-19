import api from './api'
import type { Workshop, WorkshopSearchFilters } from '../types/workshop'

export class WorkshopService {
  async search(filters: WorkshopSearchFilters): Promise<Workshop[]> {
    const params = new URLSearchParams({
      latitude: filters.latitude.toString(),
      longitude: filters.longitude.toString(),
      radius_km: (filters.radius_km || 50).toString(),
    })

    if (filters.min_rating) {
      params.append('min_rating', filters.min_rating.toString())
    }

    if (filters.services?.length) {
      filters.services.forEach(s => params.append('services[]', s))
    }

    const response = await api.get<Workshop[]>(`/workshops/search?${params}`)
    if (response.success && response.data) {
      return response.data
    }
    throw new Error('Failed to search workshops')
  }

  async getById(id: number): Promise<Workshop> {
    const response = await api.get<Workshop>(`/workshops/${id}`)
    if (response.success && response.data) {
      return response.data
    }
    throw new Error('Failed to fetch workshop')
  }
}

export default new WorkshopService()
