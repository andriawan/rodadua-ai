import type { ApiResponse } from '../types/api'

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

class ApiClient {
  private token: string | null = null

  constructor() {
    this.token = localStorage.getItem('auth_token')
  }

  setToken(token: string) {
    this.token = token
    localStorage.setItem('auth_token', token)
  }

  clearToken() {
    this.token = null
    localStorage.removeItem('auth_token')
  }

  private getHeaders(): Record<string, string> {
    const headers: Record<string, string> = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    }
    if (this.token) {
      headers['Authorization'] = `Bearer ${this.token}`
    }
    return headers
  }

  async request<T>(
    method: string,
    endpoint: string,
    data?: unknown,
  ): Promise<ApiResponse<T>> {
    const url = `${API_BASE_URL}${endpoint}`
    const options: RequestInit = {
      method,
      headers: this.getHeaders(),
    }

    if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
      options.body = JSON.stringify(data)
    }

    try {
      const response = await fetch(url, options)
      
      if (response.status === 401) {
        this.clearToken()
        window.location.href = '/login'
      }

      const json = await response.json()
      return json as ApiResponse<T>
    } catch (error) {
      console.error('API request failed:', error)
      throw error
    }
  }

  get<T>(endpoint: string) {
    return this.request<T>('GET', endpoint)
  }

  post<T>(endpoint: string, data?: unknown) {
    return this.request<T>('POST', endpoint, data)
  }

  put<T>(endpoint: string, data?: unknown) {
    return this.request<T>('PUT', endpoint, data)
  }

  patch<T>(endpoint: string, data?: unknown) {
    return this.request<T>('PATCH', endpoint, data)
  }

  delete<T>(endpoint: string) {
    return this.request<T>('DELETE', endpoint)
  }
}

export default new ApiClient()
