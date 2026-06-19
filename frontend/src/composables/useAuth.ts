import { useAuthStore } from '../stores/authStore'

export function useAuth() {
  const authStore = useAuthStore()

  async function login(email: string, password: string) {
    const success = await authStore.login(email, password)
    if (success) {
      return { success: true }
    }
    return { success: false, error: authStore.error }
  }

  async function register(name: string, email: string, password: string) {
    const success = await authStore.register(name, email, password)
    if (success) {
      return { success: true }
    }
    return { success: false, error: authStore.error }
  }

  async function logout() {
    await authStore.logout()
  }

  return {
    user: authStore.user,
    isAuthenticated: authStore.isAuthenticated,
    loading: authStore.loading,
    error: authStore.error,
    login,
    register,
    logout,
  }
}
