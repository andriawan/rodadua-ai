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
