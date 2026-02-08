<template>
  <div class="min-h-screen bg-slate-100">
    <AppNavBar />
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
          v-for="task in tasksStore.tasks"
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

      <div
        v-if="tasksStore.meta && tasksStore.meta.last_page > 1"
        class="mt-6 flex items-center justify-between"
      >
        <p class="text-sm text-slate-600">
          Показано {{ tasksStore.meta.from }}–{{ tasksStore.meta.to }} из {{ tasksStore.meta.total }}
        </p>
        <div class="flex gap-2">
          <button
            :disabled="!tasksStore.meta || tasksStore.meta.current_page <= 1"
            @click="tasksStore.goToPage(tasksStore.meta!.current_page - 1)"
            class="px-3 py-1 border rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-100"
          >
            Назад
          </button>
          <button
            :disabled="!tasksStore.meta || tasksStore.meta.current_page >= tasksStore.meta.last_page"
            @click="tasksStore.goToPage(tasksStore.meta!.current_page + 1)"
            class="px-3 py-1 border rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-100"
          >
            Вперёд
          </button>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { catFactApi } from '@/api/catFact'
import { useTasksStore } from '@/stores/tasks'
import AppNavBar from '@/components/AppNavBar.vue'
import type { CatFact, Task } from '@/types'

const tasksStore = useTasksStore()
const newTitle = ref('')
const newStatus = ref('new')
const catFact = ref<CatFact | null>(null)

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
  await tasksStore.createTask(newTitle.value.trim(), newStatus.value)
  newTitle.value = ''
  newStatus.value = 'new'
}

async function handleStatusChange(task: Task, status: string) {
  await tasksStore.updateTask(task, status)
}

async function handleDelete(id: number) {
  await tasksStore.deleteTask(id)
}

onMounted(() => {
  tasksStore.loadTasks()
  loadCatFact()
})
</script>
