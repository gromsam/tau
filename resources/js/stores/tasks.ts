import { defineStore } from 'pinia'
import { ref } from 'vue'
import { tasksApi } from '@/api/tasks'
import type { PaginatedResponse, Task } from '@/types'

export const useTasksStore = defineStore('tasks', () => {
  const tasks = ref<Task[]>([])
  const meta = ref<PaginatedResponse<Task>['meta'] | null>(null)
  const perPage = 10

  async function loadTasks(page = 1) {
    const { data } = await tasksApi.list({ per_page: perPage, page })
    tasks.value = data.data ?? []
    meta.value = data.meta ?? null
  }

  async function createTask(title: string, status: string) {
    await tasksApi.create({ title, status })
    await loadTasks(meta.value?.current_page ?? 1)
  }

  async function updateTask(task: Task, status: string) {
    await tasksApi.update(task.id, { status })
    task.status = status
  }

  async function deleteTask(id: number) {
    await tasksApi.delete(id)
    const currentPage = meta.value?.current_page ?? 1
    const remaining = tasks.value.filter((t) => t.id !== id)
    if (remaining.length === 0 && currentPage > 1) {
      await loadTasks(currentPage - 1)
    } else {
      tasks.value = remaining
    }
  }

  function goToPage(page: number) {
    if (page < 1 || (meta.value && page > meta.value.last_page)) return
    loadTasks(page)
  }

  return {
    tasks,
    meta,
    perPage,
    loadTasks,
    createTask,
    updateTask,
    deleteTask,
    goToPage,
  }
})
