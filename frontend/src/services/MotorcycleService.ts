import api from './api'
import type { Motorcycle, CreateMotorcycleInput, UpdateMotorcycleInput } from '../types/motorcycle'
import type { PaginatedResponse } from '../types/api'

export class MotorcycleService {
  async getAll(page = 1): Promise<PaginatedResponse<Motorcycle>> {
    const response = await api.get<PaginatedResponse<Motorcycle>>(`/motorcycles?page=${page}`)
    if (response.success && response.data) {
      return response.data
    }
    throw new Error('Failed to fetch motorcycles')
  }

  async getById(id: number): Promise<Motorcycle> {
    const response = await api.get<Motorcycle>(`/motorcycles/${id}`)
    if (response.success && response.data) {
      return response.data
    }
    throw new Error('Failed to fetch motorcycle')
  }

  async create(data: CreateMotorcycleInput): Promise<Motorcycle> {
    const response = await api.post<Motorcycle>('/motorcycles', data)
    if (response.success && response.data) {
      return response.data
    }
    throw new Error(response.error?.details?.message?.[0] || 'Failed to create motorcycle')
  }

  async update(id: number, data: UpdateMotorcycleInput): Promise<Motorcycle> {
    const response = await api.put<Motorcycle>(`/motorcycles/${id}`, data)
    if (response.success && response.data) {
      return response.data
    }
    throw new Error('Failed to update motorcycle')
  }

  async delete(id: number): Promise<void> {
    await api.delete(`/motorcycles/${id}`)
  }

  async toggleFavorite(id: number): Promise<Motorcycle> {
    const response = await api.patch<Motorcycle>(`/motorcycles/${id}/toggle-favorite`, {})
    if (response.success && response.data) {
      return response.data
    }
    throw new Error('Failed to toggle favorite')
  }
}

export default new MotorcycleService()
