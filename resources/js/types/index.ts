export interface User {
  id: number
  name: string
  email: string
  is_admin?: boolean
}

export interface Task {
  id: number
  title: string
  status: string
  user_id: number
  created_at: string
  updated_at: string
}

export interface PaginatedResponse<T> {
  data: T[]
  links: {
    first: string
    last: string
    prev: string | null
    next: string | null
  }
  meta: {
    current_page: number
    from: number | null
    last_page: number
    path: string
    per_page: number
    to: number | null
    total: number
  }
}

export interface Stats {
  total: number
  by_status: Record<string, number>
}

export interface CatFact {
  fact: string
  length: number
}
