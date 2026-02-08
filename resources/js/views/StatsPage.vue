<template>
  <div class="min-h-screen bg-slate-100">
    <nav class="bg-white shadow p-4 flex justify-between items-center">
      <div class="flex gap-4">
        <router-link to="/" class="text-slate-600 hover:text-slate-900">Tasks</router-link>
        <router-link to="/stats" class="text-blue-600 font-medium">Stats</router-link>
      </div>
      <button
        @click="handleLogout"
        class="text-slate-600 hover:text-slate-900"
      >
        Logout
      </button>
    </nav>
    <main class="max-w-2xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-6">Stats</h1>
      <div v-if="stats" class="space-y-4">
        <div class="p-4 bg-white rounded shadow">
          <p class="text-slate-600">Total tasks</p>
          <p class="text-2xl font-bold">{{ stats.total }}</p>
        </div>
        <div class="p-4 bg-white rounded shadow">
          <p class="text-slate-600 mb-2">By status</p>
          <ul class="space-y-1">
            <li v-for="(count, status) in stats.by_status" :key="status" class="flex justify-between">
              <span>{{ status }}</span>
              <span>{{ count }}</span>
            </li>
          </ul>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { statsApi } from '@/api/stats'
import { authApi } from '@/api/auth'
import type { Stats } from '@/types'
import { useRouter } from 'vue-router'

const router = useRouter()
const stats = ref<Stats | null>(null)

async function loadStats() {
  const { data } = await statsApi.get()
  stats.value = data.data ?? null
}

async function handleLogout() {
  await authApi.logout()
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  router.push('/login')
}

onMounted(() => {
  loadStats()
})
</script>
