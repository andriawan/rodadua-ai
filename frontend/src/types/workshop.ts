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
