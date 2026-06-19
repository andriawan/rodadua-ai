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
