import api from './client'
import type { User } from '@/types'

export const authApi = {
  login: (email: string, password: string) =>
    api.post<{ user: User; token: string }>('/login', { email, password }),

  logout: () => api.post('/logout'),

  user: () => api.get<{ data: User }>('/user'),
}
