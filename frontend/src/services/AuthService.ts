import api from './api'
import type { User, AuthToken } from '../types/user'

export class AuthService {
  async login(email: string, password: string): Promise<{ user: User; token: AuthToken }> {
    const response = await api.post<{ user: User; token: AuthToken }>('/auth/login', {
      email,
      password,
    })
    
    if (response.success && response.data) {
      api.setToken(response.data.token.token)
      return response.data
    }
    throw new Error(response.error?.details?.message?.[0] || 'Login failed')
  }

  async register(name: string, email: string, password: string): Promise<User> {
    const response = await api.post<User>('/auth/register', {
      name,
      email,
      password,
    })
    
    if (response.success && response.data) {
      return response.data
    }
    throw new Error('Registration failed')
  }

  async logout(): Promise<void> {
    await api.post('/auth/logout')
    api.clearToken()
  }

  async getProfile(): Promise<User> {
    const response = await api.get<User>('/auth/profile')
    if (response.success && response.data) {
      return response.data
    }
    throw new Error('Failed to fetch profile')
  }
}

export default new AuthService()
