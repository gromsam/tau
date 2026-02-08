export interface User {
  id: number
  name: string
  email: string
}

export interface Task {
  id: number
  title: string
  status: string
  user_id: number
  created_at: string
  updated_at: string
}

export interface Stats {
  total: number
  by_status: Record<string, number>
}

export interface CatFact {
  fact: string
  length: number
}
