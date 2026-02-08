<template>
  <div class="min-h-screen bg-slate-100">
    <nav class="bg-white shadow p-4 flex justify-between items-center">
      <div class="flex gap-4">
        <router-link to="/" class="text-blue-600 font-medium">Tasks</router-link>
        <router-link to="/stats" class="text-slate-600 hover:text-slate-900">Stats</router-link>
      </div>
      <button
        @click="handleLogout"
        class="text-slate-600 hover:text-slate-900"
      >
        Logout
      </button>
    </nav>
    <main class="max-w-2xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-6">Tasks</h1>

      <div v-if="catFact" class="mb-6 p-4 bg-amber-50 rounded-lg border border-amber-200">
        <p class="text-sm font-medium text-amber-800 mb-1">Cat fact</p>
        <p class="text-slate-700">{{ catFact.fact }}</p>
      </div>

      <form @submit.prevent="handleCreate" class="flex gap-2 mb-6">
        <input
          v-model="newTitle"
          type="text"
          placeholder="New task title"
          class="flex-1 px-3 py-2 border rounded"
        />
        <select v-model="newStatus" class="px-3 py-2 border rounded">
          <option value="new">New</option>
          <option value="in_progress">In Progress</option>
          <option value="done">Done</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
          Add
        </button>
      </form>

      <ul class="space-y-2">
        <li
          v-for="task in tasks"
          :key="task.id"
          class="flex items-center gap-4 p-4 bg-white rounded shadow"
        >
          <span class="flex-1">{{ task.title }}</span>
          <select
            :value="task.status"
            @change="(e) => handleStatusChange(task, (e.target as HTMLSelectElement).value)"
            class="px-2 py-1 border rounded"
          >
            <option value="new">New</option>
            <option value="in_progress">In Progress</option>
            <option value="done">Done</option>
          </select>
          <button
            @click="handleDelete(task.id)"
            class="text-red-600 hover:text-red-800"
          >
            Delete
          </button>
        </li>
      </ul>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { tasksApi } from '@/api/tasks'
import { authApi } from '@/api/auth'
import { catFactApi } from '@/api/catFact'
import type { Task, CatFact } from '@/types'
import { useRouter } from 'vue-router'

const router = useRouter()
const tasks = ref<Task[]>([])
const newTitle = ref('')
const newStatus = ref('new')
const catFact = ref<CatFact | null>(null)

async function loadTasks() {
  const { data } = await tasksApi.list()
  tasks.value = data.data ?? []
}

async function loadCatFact() {
  try {
    const { data } = await catFactApi.get()
    catFact.value = data
  } catch {
    // ignore
  }
}

async function handleCreate() {
  if (!newTitle.value.trim()) return
  await tasksApi.create({ title: newTitle.value.trim(), status: newStatus.value })
  newTitle.value = ''
  newStatus.value = 'new'
  await loadTasks()
}

async function handleStatusChange(task: Task, status: string) {
  await tasksApi.update(task.id, { status })
  task.status = status
}

async function handleDelete(id: number) {
  await tasksApi.delete(id)
  tasks.value = tasks.value.filter((t) => t.id !== id)
}

async function handleLogout() {
  await authApi.logout()
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  router.push('/login')
}

onMounted(() => {
  loadTasks()
  loadCatFact()
})
</script>
