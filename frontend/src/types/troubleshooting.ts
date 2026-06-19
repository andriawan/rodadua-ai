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
