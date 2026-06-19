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
