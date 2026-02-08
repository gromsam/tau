import api from './client'
import type { Task } from '@/types'

export const tasksApi = {
  list: (params?: { per_page?: number }) =>
    api.get<{ data: Task[] }>('/tasks', { params }),

  create: (data: { title: string; status: string }) =>
    api.post<{ data: Task }>('/tasks', data),

  update: (id: number, data: { title?: string; status?: string }) =>
    api.put<{ data: Task }>(`/tasks/${id}`, data),

  delete: (id: number) => api.delete(`/tasks/${id}`),
}
