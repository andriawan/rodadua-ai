import api from './api'
import type { Maintenance, MaintenanceRecommendation } from '../types/maintenance'
import type { PaginatedResponse } from '../types/api'

export class MaintenanceService {
  async getForMotorcycle(motorcycleId: number, page = 1): Promise<PaginatedResponse<Maintenance>> {
    const response = await api.get<PaginatedResponse<Maintenance>>(
      `/motorcycles/${motorcycleId}/maintenance?page=${page}`
    )
    if (response.success && response.data) {
      return response.data
    }
    throw new Error('Failed to fetch maintenance records')
  }

  async getRecommendations(motorcycleId: number): Promise<MaintenanceRecommendation[]> {
    const response = await api.get<MaintenanceRecommendation[]>(
      `/motorcycles/${motorcycleId}/maintenance/recommendations`
    )
    if (response.success && response.data) {
      return response.data
    }
    throw new Error('Failed to fetch recommendations')
  }

  async create(motorcycleId: number, data: Partial<Maintenance>): Promise<Maintenance> {
    const response = await api.post<Maintenance>(`/motorcycles/${motorcycleId}/maintenance`, data)
    if (response.success && response.data) {
      return response.data
    }
    throw new Error('Failed to create maintenance record')
  }

  async update(motorcycleId: number, maintenanceId: number, data: Partial<Maintenance>): Promise<Maintenance> {
    const response = await api.put<Maintenance>(
      `/motorcycles/${motorcycleId}/maintenance/${maintenanceId}`,
      data
    )
    if (response.success && response.data) {
      return response.data
    }
    throw new Error('Failed to update maintenance record')
  }

  async delete(motorcycleId: number, maintenanceId: number): Promise<void> {
    await api.delete(`/motorcycles/${motorcycleId}/maintenance/${maintenanceId}`)
  }
}

export default new MaintenanceService()
