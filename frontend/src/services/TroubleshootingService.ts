import api from './api'
import type { TroubleshootingEntry, CreateTroubleshootingInput } from '../types/troubleshooting'
import type { PaginatedResponse } from '../types/api'

export class TroubleshootingService {
  async getForMotorcycle(motorcycleId: number, page = 1): Promise<PaginatedResponse<TroubleshootingEntry>> {
    const response = await api.get<PaginatedResponse<TroubleshootingEntry>>(
      `/motorcycles/${motorcycleId}/troubleshooting?page=${page}`
    )
    if (response.success && response.data) {
      return response.data
    }
    throw new Error('Failed to fetch troubleshooting history')
  }

  async analyze(motorcycleId: number, data: CreateTroubleshootingInput): Promise<TroubleshootingEntry> {
    const response = await api.post<TroubleshootingEntry>(
      `/motorcycles/${motorcycleId}/troubleshooting/analyze`,
      data
    )
    if (response.success && response.data) {
      return response.data
    }
    throw new Error('Failed to analyze problem')
  }

  async updateResolution(motorcycleId: number, entryId: number, notes: string, rating: number): Promise<TroubleshootingEntry> {
    const response = await api.patch<TroubleshootingEntry>(
      `/motorcycles/${motorcycleId}/troubleshooting/${entryId}`,
      { resolution_notes: notes, user_rating: rating }
    )
    if (response.success && response.data) {
      return response.data
    }
    throw new Error('Failed to update resolution')
  }
}

export default new TroubleshootingService()
