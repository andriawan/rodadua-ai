# Phase 4: Frontend Architecture - Setup Guide

## Overview
Build a scalable, type-safe frontend with Vue 3 + TypeScript using Pinia for state management, composables for reusable logic, and a component-based architecture. All components must stay under 300 lines.

**Estimated Time**: 4-5 days  
**Key Focus**: Design system consistency, type safety, API integration layer, reusable composables

---

## Part 1: TypeScript Types Foundation

### 1.1 Create Type Files

Create `frontend/src/types/` directory with the following files:

#### `types/api.ts` - HTTP Response Types
```typescript
export interface ApiResponse<T> {
  success: boolean
  message: string
  data?: T
  error?: {
    code: string
    details?: Record<string, string[]>
  }
  meta?: {
    pagination?: {
      current_page: number
      per_page: number
      total: number
      last_page: number
    }
  }
}

export interface PaginatedResponse<T> {
  data: T[]
  current_page: number
  per_page: number
  total: number
  last_page: number
}
```

#### `types/user.ts` - User Types
```typescript
export interface User {
  id: number
  name: string
  email: string
  phone?: string
  profile_picture?: string
  role: 'user' | 'admin' | 'workshop'
  created_at: string
  updated_at: string
}

export interface AuthToken {
  token: string
  type: 'Bearer'
  expires_in: number
}
```

#### `types/motorcycle.ts` - Motorcycle Types
```typescript
export interface Motorcycle {
  id: number
  user_id: number
  brand: string
  model: string
  year: number
  color: string
  license_plate: string
  engine_cc: number
  engine_type: string // 'single_cylinder', 'twin_cylinder', etc.
  transmission: string // 'manual', 'automatic'
  fuel_type: string // 'petrol', 'diesel', 'electric'
  purchase_date: string
  odometer_km: number
  notes?: string
  status: 'active' | 'inactive' | 'for_sale'
  is_favorite: boolean
  created_at: string
  updated_at: string
}

export interface CreateMotorcycleInput {
  brand: string
  model: string
  year: number
  color: string
  license_plate: string
  engine_cc: number
  engine_type: string
  transmission: string
  fuel_type: string
  purchase_date: string
  odometer_km: number
  notes?: string
}

export interface UpdateMotorcycleInput extends Partial<CreateMotorcycleInput> {
  status?: 'active' | 'inactive' | 'for_sale'
}
```

#### `types/maintenance.ts` - Maintenance Types
```typescript
export interface Maintenance {
  id: number
  motorcycle_id: number
  user_id: number
  type: string // 'oil_change', 'tire_replacement', etc.
  description: string
  odometer_km: number
  maintenance_date: string
  cost?: number
  workshop?: string
  status: 'scheduled' | 'completed' | 'pending'
  next_maintenance_km?: number
  next_maintenance_date?: string
  notes?: string
  created_at: string
  updated_at: string
}

export interface MaintenanceRecommendation {
  type: string
  description: string
  estimated_cost: number
  estimated_timing: string
  priority: 'low' | 'medium' | 'high' | 'critical'
}
```

#### `types/troubleshooting.ts` - Troubleshooting Types
```typescript
export interface TroubleshootingEntry {
  id: number
  motorcycle_id: number
  user_id: number
  problem_description: string
  symptom: string
  ai_analysis: string
  suggested_solutions: Solution[]
  severity: 'low' | 'medium' | 'high' | 'critical'
  status: 'open' | 'resolved' | 'in_progress'
  resolution_notes?: string
  user_rating?: number
  user_feedback?: string
  created_at: string
  updated_at: string
}

export interface Solution {
  step: number
  description: string
  estimated_cost?: number
  difficulty: 'easy' | 'medium' | 'hard'
  requires_professional: boolean
}

export interface CreateTroubleshootingInput {
  problem_description: string
  symptom: string
}
```

#### `types/workshop.ts` - Workshop Types
```typescript
export interface Workshop {
  id: number
  name: string
  description?: string
  phone: string
  email: string
  website?: string
  address: string
  city: string
  province: string
  postal_code: string
  latitude: number
  longitude: number
  rating: number
  total_reviews: number
  specialist_motorcycle_count: number
  operating_hours: string
  is_open_weekends: boolean
  services_offered: string[]
  created_at: string
  updated_at: string
}

export interface WorkshopSearchFilters {
  latitude: number
  longitude: number
  radius_km?: number
  min_rating?: number
  services?: string[]
}
```

---

## Part 2: Design System & Tailwind Configuration

### 2.1 Update `frontend/tailwind.config.js`
```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        // Primary colors (motorcycle/performance theme)
        primary: {
          50: '#f5f3ff',
          100: '#ede9fe',
          200: '#ddd6fe',
          300: '#c4b5fd',
          400: '#a78bfa',
          500: '#8b5cf6', // primary
          600: '#7c3aed',
          700: '#6d28d9',
          800: '#5b21b6',
          900: '#4c1d95',
        },
        // Secondary (action/status)
        secondary: {
          50: '#f0fdf4',
          100: '#dcfce7',
          200: '#bbf7d0',
          300: '#86efac',
          400: '#4ade80',
          500: '#22c55e', // secondary
          600: '#16a34a',
          700: '#15803d',
          800: '#166534',
          900: '#145231',
        },
        // Semantic colors
        danger: {
          50: '#fef2f2',
          500: '#ef4444',
          700: '#b91c1c',
        },
        warning: {
          50: '#fffbeb',
          500: '#f59e0b',
          700: '#b45309',
        },
        success: {
          50: '#f0fdf4',
          500: '#10b981',
          700: '#047857',
        },
        info: {
          50: '#eff6ff',
          500: '#3b82f6',
          700: '#1d4ed8',
        },
        // Neutral
        neutral: {
          50: '#fafafa',
          100: '#f5f5f5',
          200: '#e5e5e5',
          300: '#d4d4d4',
          400: '#a3a3a3',
          500: '#737373',
          600: '#525252',
          700: '#404040',
          800: '#262626',
          900: '#171717',
        },
      },
      spacing: {
        // Design tokens: 4, 8, 12, 16, 24, 32, 48, 64
        // Already mapped: 4, 8, 12, 16, 20, 24, 28, 32
        48: '12rem',
        64: '16rem',
      },
      fontSize: {
        // Typography scale
        'xs': ['0.75rem', { lineHeight: '1rem' }],
        'sm': ['0.875rem', { lineHeight: '1.25rem' }],
        'base': ['1rem', { lineHeight: '1.5rem' }],
        'lg': ['1.125rem', { lineHeight: '1.75rem' }],
        'xl': ['1.25rem', { lineHeight: '1.75rem' }],
        '2xl': ['1.5rem', { lineHeight: '2rem' }],
        '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
        '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
      },
      fontWeight: {
        light: '300',
        normal: '400',
        medium: '500',
        semibold: '600',
        bold: '700',
      },
      borderRadius: {
        'sm': '0.25rem',
        'base': '0.375rem',
        'md': '0.5rem',
        'lg': '0.75rem',
        'xl': '1rem',
      },
      shadows: {
        'sm': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
        'base': '0 1px 3px 0 rgba(0, 0, 0, 0.1)',
        'md': '0 4px 6px -1px rgba(0, 0, 0, 0.1)',
        'lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
      },
    },
  },
  plugins: [],
}
```

### 2.2 Create Design System Documentation
Create `frontend/src/DESIGN_TOKENS.md`:
- Document all color tokens with usage examples
- Spacing scale: 4px, 8px, 12px, 16px, 24px, 32px, 48px, 64px
- Typography scale (font sizes and weights)
- Shadow system
- Border radius tokens
- State colors: primary, secondary, danger, warning, success, info

---

## Part 3: Core Services

### 3.1 Create `frontend/src/services/api.ts`
```typescript
import type { ApiResponse, PaginatedResponse } from '../types/api'

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
```

### 3.2 Create Service Classes

#### `frontend/src/services/AuthService.ts`
```typescript
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
```

#### `frontend/src/services/MotorcycleService.ts`
```typescript
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
```

#### `frontend/src/services/MaintenanceService.ts`
```typescript
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
```

#### `frontend/src/services/TroubleshootingService.ts`
```typescript
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
```

#### `frontend/src/services/WorkshopService.ts`
```typescript
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
```

---

## Part 4: Pinia Stores

### 4.1 Create `frontend/src/stores/authStore.ts`
```typescript
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { User } from '../types/user'
import AuthService from '../services/AuthService'

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const isAuthenticated = computed(() => user.value !== null)
  const loading = ref(false)
  const error = ref<string | null>(null)

  async function login(email: string, password: string) {
    loading.value = true
    error.value = null
    try {
      const { user: userData } = await AuthService.login(email, password)
      user.value = userData
      return true
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Login failed'
      return false
    } finally {
      loading.value = false
    }
  }

  async function register(name: string, email: string, password: string) {
    loading.value = true
    error.value = null
    try {
      await AuthService.register(name, email, password)
      return true
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Registration failed'
      return false
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try {
      await AuthService.logout()
      user.value = null
    } catch (err) {
      console.error('Logout error:', err)
    }
  }

  async function fetchProfile() {
    loading.value = true
    try {
      user.value = await AuthService.getProfile()
    } catch (err) {
      console.error('Failed to fetch profile:', err)
    } finally {
      loading.value = false
    }
  }

  return {
    user,
    isAuthenticated,
    loading,
    error,
    login,
    register,
    logout,
    fetchProfile,
  }
})
```

### 4.2 Create `frontend/src/stores/motorcycleStore.ts`
```typescript
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { Motorcycle } from '../types/motorcycle'
import MotorcycleService from '../services/MotorcycleService'

export const useMotorcycleStore = defineStore('motorcycle', () => {
  const motorcycles = ref<Motorcycle[]>([])
  const currentMotorcycle = ref<Motorcycle | null>(null)
  const favorites = ref<number[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)
  const pagination = ref({ page: 1, total: 0, per_page: 15, last_page: 1 })

  const allFavorites = computed(() => 
    motorcycles.value.filter(m => m.is_favorite)
  )

  async function fetchAll(page = 1) {
    loading.value = true
    error.value = null
    try {
      const result = await MotorcycleService.getAll(page)
      motorcycles.value = result.data
      pagination.value = {
        page: result.current_page,
        total: result.total,
        per_page: result.per_page,
        last_page: result.last_page,
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to fetch motorcycles'
    } finally {
      loading.value = false
    }
  }

  async function fetchById(id: number) {
    loading.value = true
    error.value = null
    try {
      currentMotorcycle.value = await MotorcycleService.getById(id)
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to fetch motorcycle'
    } finally {
      loading.value = false
    }
  }

  async function create(data: any) {
    loading.value = true
    error.value = null
    try {
      const newMoto = await MotorcycleService.create(data)
      motorcycles.value.unshift(newMoto)
      return newMoto
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to create motorcycle'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function update(id: number, data: any) {
    loading.value = true
    error.value = null
    try {
      const updated = await MotorcycleService.update(id, data)
      const index = motorcycles.value.findIndex(m => m.id === id)
      if (index >= 0) {
        motorcycles.value[index] = updated
      }
      if (currentMotorcycle.value?.id === id) {
        currentMotorcycle.value = updated
      }
      return updated
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to update motorcycle'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function delete_motorcycle(id: number) {
    loading.value = true
    error.value = null
    try {
      await MotorcycleService.delete(id)
      motorcycles.value = motorcycles.value.filter(m => m.id !== id)
      if (currentMotorcycle.value?.id === id) {
        currentMotorcycle.value = null
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to delete motorcycle'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function toggleFavorite(id: number) {
    try {
      const updated = await MotorcycleService.toggleFavorite(id)
      const index = motorcycles.value.findIndex(m => m.id === id)
      if (index >= 0) {
        motorcycles.value[index] = updated
      }
      if (currentMotorcycle.value?.id === id) {
        currentMotorcycle.value = updated
      }
    } catch (err) {
      console.error('Failed to toggle favorite:', err)
    }
  }

  return {
    motorcycles,
    currentMotorcycle,
    favorites,
    allFavorites,
    loading,
    error,
    pagination,
    fetchAll,
    fetchById,
    create,
    update,
    delete_motorcycle,
    toggleFavorite,
  }
})
```

### 4.3 Create `frontend/src/stores/uiStore.ts`
```typescript
import { defineStore } from 'pinia'
import { ref } from 'vue'

export interface Modal {
  isOpen: boolean
  title: string
  message?: string
  onConfirm?: () => void
  onCancel?: () => void
}

export const useUiStore = defineStore('ui', () => {
  const loadingStates = ref<Record<string, boolean>>({})
  const notifications = ref<Array<{
    id: string
    type: 'success' | 'error' | 'warning' | 'info'
    message: string
    timeout?: number
  }>>([])
  const modal = ref<Modal>({
    isOpen: false,
    title: '',
  })

  function setLoading(key: string, isLoading: boolean) {
    loadingStates.value[key] = isLoading
  }

  function isLoading(key: string): boolean {
    return loadingStates.value[key] ?? false
  }

  function notify(type: 'success' | 'error' | 'warning' | 'info', message: string, timeout = 3000) {
    const id = Date.now().toString()
    notifications.value.push({ id, type, message, timeout })
    
    if (timeout > 0) {
      setTimeout(() => {
        dismissNotification(id)
      }, timeout)
    }
  }

  function dismissNotification(id: string) {
    const index = notifications.value.findIndex(n => n.id === id)
    if (index >= 0) {
      notifications.value.splice(index, 1)
    }
  }

  function openModal(title: string, message?: string, onConfirm?: () => void, onCancel?: () => void) {
    modal.value = {
      isOpen: true,
      title,
      message,
      onConfirm,
      onCancel,
    }
  }

  function closeModal() {
    modal.value.isOpen = false
  }

  return {
    loadingStates,
    notifications,
    modal,
    setLoading,
    isLoading,
    notify,
    dismissNotification,
    openModal,
    closeModal,
  }
})
```

---

## Part 5: Core Composables

### 5.1 Create `frontend/src/composables/useAuth.ts`
```typescript
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
```

### 5.2 Create `frontend/src/composables/useMotorcycle.ts`
```typescript
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
```

### 5.3 Create `frontend/src/composables/useMaintenance.ts`
```typescript
import { ref } from 'vue'
import MaintenanceService from '../services/MaintenanceService'
import type { Maintenance, MaintenanceRecommendation } from '../types/maintenance'
import { useUiStore } from '../stores/uiStore'

export function useMaintenance() {
  const uiStore = useUiStore()
  const maintenance = ref<Maintenance[]>([])
  const recommendations = ref<MaintenanceRecommendation[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  async function fetchForMotorcycle(motorcycleId: number, page = 1) {
    loading.value = true
    error.value = null
    try {
      const result = await MaintenanceService.getForMotorcycle(motorcycleId, page)
      maintenance.value = result.data
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to fetch maintenance'
      uiStore.notify('error', error.value)
    } finally {
      loading.value = false
    }
  }

  async function fetchRecommendations(motorcycleId: number) {
    loading.value = true
    error.value = null
    try {
      recommendations.value = await MaintenanceService.getRecommendations(motorcycleId)
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to fetch recommendations'
    } finally {
      loading.value = false
    }
  }

  async function addMaintenance(motorcycleId: number, data: Partial<Maintenance>) {
    loading.value = true
    error.value = null
    try {
      const newRecord = await MaintenanceService.create(motorcycleId, data)
      maintenance.value.unshift(newRecord)
      uiStore.notify('success', 'Maintenance record added')
      return newRecord
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to add maintenance'
      uiStore.notify('error', error.value)
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    maintenance,
    recommendations,
    loading,
    error,
    fetchForMotorcycle,
    fetchRecommendations,
    addMaintenance,
  }
}
```

### 5.4 Create `frontend/src/composables/useTroubleshooting.ts`
```typescript
import { ref } from 'vue'
import TroubleshootingService from '../services/TroubleshootingService'
import type { TroubleshootingEntry, CreateTroubleshootingInput } from '../types/troubleshooting'
import { useUiStore } from '../stores/uiStore'

export function useTroubleshooting() {
  const uiStore = useUiStore()
  const entries = ref<TroubleshootingEntry[]>([])
  const loading = ref(false)
  const analyzing = ref(false)
  const error = ref<string | null>(null)

  async function fetchForMotorcycle(motorcycleId: number, page = 1) {
    loading.value = true
    error.value = null
    try {
      const result = await TroubleshootingService.getForMotorcycle(motorcycleId, page)
      entries.value = result.data
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to fetch troubleshooting history'
      uiStore.notify('error', error.value)
    } finally {
      loading.value = false
    }
  }

  async function analyze(motorcycleId: number, data: CreateTroubleshootingInput) {
    analyzing.value = true
    error.value = null
    try {
      const entry = await TroubleshootingService.analyze(motorcycleId, data)
      entries.value.unshift(entry)
      uiStore.notify('success', 'Analysis complete')
      return entry
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to analyze problem'
      uiStore.notify('error', error.value)
      throw err
    } finally {
      analyzing.value = false
    }
  }

  return {
    entries,
    loading,
    analyzing,
    error,
    fetchForMotorcycle,
    analyze,
  }
}
```

### 5.5 Create `frontend/src/composables/useWorkshop.ts`
```typescript
import { ref } from 'vue'
import WorkshopService from '../services/WorkshopService'
import type { Workshop, WorkshopSearchFilters } from '../types/workshop'
import { useUiStore } from '../stores/uiStore'

export function useWorkshop() {
  const uiStore = useUiStore()
  const workshops = ref<Workshop[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  async function search(filters: WorkshopSearchFilters) {
    loading.value = true
    error.value = null
    try {
      workshops.value = await WorkshopService.search(filters)
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to search workshops'
      uiStore.notify('error', error.value)
    } finally {
      loading.value = false
    }
  }

  async function getById(id: number) {
    loading.value = true
    error.value = null
    try {
      return await WorkshopService.getById(id)
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to fetch workshop'
      uiStore.notify('error', error.value)
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    workshops,
    loading,
    error,
    search,
    getById,
  }
}
```

---

## Part 6: Implementation Checklist

### Create `PHASE_4_CHECKLIST.md`

Track your progress with these items:

- [ ] **TypeScript Types** (30 min)
  - [ ] types/api.ts
  - [ ] types/user.ts
  - [ ] types/motorcycle.ts
  - [ ] types/maintenance.ts
  - [ ] types/troubleshooting.ts
  - [ ] types/workshop.ts

- [ ] **Design System** (45 min)
  - [ ] Update tailwind.config.js with color tokens
  - [ ] Document spacing scale (4, 8, 12, 16, 24, 32, 48, 64)
  - [ ] Document typography scale
  - [ ] Create DESIGN_TOKENS.md

- [ ] **Core Services** (2 hours)
  - [ ] api.ts (HTTP client)
  - [ ] AuthService.ts
  - [ ] MotorcycleService.ts
  - [ ] MaintenanceService.ts
  - [ ] TroubleshootingService.ts
  - [ ] WorkshopService.ts

- [ ] **Pinia Stores** (1.5 hours)
  - [ ] authStore.ts
  - [ ] motorcycleStore.ts
  - [ ] uiStore.ts

- [ ] **Core Composables** (1 hour)
  - [ ] useAuth.ts
  - [ ] useMotorcycle.ts
  - [ ] useMaintenance.ts
  - [ ] useTroubleshooting.ts
  - [ ] useWorkshop.ts

- [ ] **Base Components** (2 hours)
  - [ ] Create components directory structure
  - [ ] BaseButton.vue
  - [ ] BaseInput.vue
  - [ ] BaseCard.vue
  - [ ] LoadingSpinner.vue
  - [ ] ErrorAlert.vue

- [ ] **Feature Components** (2 hours)
  - [ ] MotorcycleCard.vue
  - [ ] MotorcycleForm.vue
  - [ ] MaintenanceCard.vue
  - [ ] RecommendationList.vue
  - [ ] WorkshopCard.vue

- [ ] **Pages/Views Structure** (1 hour)
  - [ ] Create pages directory
  - [ ] Create layout components (MainLayout, AuthLayout)
  - [ ] Create page stubs (Home, List, Detail, etc.)

- [ ] **Testing & Validation**
  - [ ] Verify types are strict (no `any`)
  - [ ] Test API client with backend
  - [ ] Test Pinia stores
  - [ ] Test composables

---

## Next Steps

After Phase 4 completion, proceed to **Phase 5: Pages & Components** where you'll:
1. Build all page components
2. Implement detailed component views
3. Wire up all services to pages
4. Test the complete UI flow
5. Implement loading states and error handling

---

## Key Rules Reminder

✅ **Always follow:**
- Components under 300 lines (preferably <200)
- No `any` in TypeScript
- API logic in services/composables, NOT in components
- Design tokens only, never hardcode colors
- Proper error handling and user feedback
- Consistent spacing and typography

