import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { AuthService } from '@/services/AuthService'
import type { User, LoginCredentials, RegisterCredentials } from '@/types/auth'

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const token = ref<string | null>(null)
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  // Check if user is authenticated
  const isAuthenticated = computed(() => !!token.value && !!user.value)

  /**
   * Initialize auth from localStorage
   */
  const initAuth = async () => {
    const savedToken = localStorage.getItem('auth_token')
    if (savedToken) {
      token.value = savedToken
      AuthService.setToken(savedToken)
      await fetchUser()
    }
  }

  /**
   * Fetch current user
   */
  const fetchUser = async () => {
    try {
      isLoading.value = true
      error.value = null
      const response = await AuthService.getUser()
      if (response.success) {
        user.value = response.data
      }
    } catch (err: any) {
      error.value = err.message || 'Failed to fetch user'
      localStorage.removeItem('auth_token')
      token.value = null
      user.value = null
    } finally {
      isLoading.value = false
    }
  }

  /**
   * Register new user
   */
  const register = async (credentials: RegisterCredentials) => {
    try {
      isLoading.value = true
      error.value = null
      const response = await AuthService.register(credentials)
      if (response.success && response.data) {
        token.value = response.data.token
        user.value = response.data.user
        return response
      }
    } catch (err: any) {
      error.value = err.message || 'Registration failed'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  /**
   * Login user
   */
  const login = async (credentials: LoginCredentials) => {
    try {
      isLoading.value = true
      error.value = null
      const response = await AuthService.login(credentials)
      if (response.success && response.data) {
        token.value = response.data.token
        user.value = response.data.user
        return response
      }
    } catch (err: any) {
      error.value = err.message || 'Login failed'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  /**
   * Logout user
   */
  const logout = async () => {
    try {
      isLoading.value = true
      error.value = null
      await AuthService.logout()
      token.value = null
      user.value = null
    } catch (err: any) {
      error.value = err.message || 'Logout failed'
    } finally {
      isLoading.value = false
    }
  }

  /**
   * Clear auth state
   */
  const clearAuth = () => {
    token.value = null
    user.value = null
    error.value = null
    AuthService.clear()
  }

  return {
    // State
    user,
    token,
    isLoading,
    error,

    // Computed
    isAuthenticated,

    // Methods
    initAuth,
    fetchUser,
    register,
    login,
    logout,
    clearAuth,
  }
})
