import api from './api'
import type { LoginCredentials, RegisterCredentials, AuthResponse, User } from '@/types/auth'

export const AuthService = {
  /**
   * Register a new user
   */
  async register(credentials: RegisterCredentials): Promise<AuthResponse> {
    const response = await api.post<AuthResponse>('/v1/auth/register', credentials)
    if (response.success && response.data) {
      localStorage.setItem('auth_token', response.data.token)
      api.getAxiosInstance().defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
    }
    return response as AuthResponse
  },

  /**
   * Login user
   */
  async login(credentials: LoginCredentials): Promise<AuthResponse> {
    const response = await api.post<AuthResponse>('/v1/auth/login', credentials)
    if (response.success && response.data) {
      localStorage.setItem('auth_token', response.data.token)
      api.getAxiosInstance().defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
    }
    return response as AuthResponse
  },

  /**
   * Get current user
   */
  async getUser(): Promise<{ success: boolean; data: User }> {
    return api.get<User>('/v1/auth/user')
  },

  /**
   * Logout user
   */
  async logout(): Promise<{ success: boolean; message: string }> {
    const response = await api.post('/v1/auth/logout')
    localStorage.removeItem('auth_token')
    delete api.getAxiosInstance().defaults.headers.common['Authorization']
    return response
  },

  /**
   * Get token from localStorage
   */
  getToken(): string | null {
    return localStorage.getItem('auth_token')
  },

  /**
   * Set token in localStorage and headers
   */
  setToken(token: string): void {
    localStorage.setItem('auth_token', token)
    api.getAxiosInstance().defaults.headers.common['Authorization'] = `Bearer ${token}`
  },

  /**
   * Check if user is authenticated
   */
  isAuthenticated(): boolean {
    return !!this.getToken()
  },

  /**
   * Clear all auth data
   */
  clear(): void {
    localStorage.removeItem('auth_token')
    delete api.getAxiosInstance().defaults.headers.common['Authorization']
  },
}
