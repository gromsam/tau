<template>
  <div class="min-h-screen bg-slate-100">
    <AppNavBar />
    <main class="max-w-2xl mx-auto px-4 py-4 sm:px-6 sm:py-6">
      <h1 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Stats</h1>
      <div v-if="stats" class="space-y-4">

        <!-- Диаграмма -->
        <div class="p-4 sm:p-5 bg-white rounded-lg shadow">
          <p class="text-slate-600 mb-4">Status</p>
          <div class="flex flex-col items-center gap-4 sm:gap-5">
            <div
              class="w-40 h-40 sm:w-48 sm:h-48 rounded-full relative shrink-0"
              :style="donutStyle"
            >
              <div
                class="absolute inset-3 sm:inset-4 rounded-full bg-white flex items-center justify-center"
              >
                <span class="text-xl sm:text-2xl font-bold text-slate-700">{{ stats.total }}</span>
              </div>
            </div>
            <div class="flex flex-wrap justify-center gap-x-4 gap-y-2 sm:gap-4 w-full sm:w-auto">
              <div
                v-for="item in chartData"
                :key="item.status"
                class="flex items-center gap-2 min-w-0"
              >
                <span
                  class="w-3 h-3 sm:w-4 sm:h-4 rounded-sm shrink-0"
                  :style="{ backgroundColor: item.color }"
                />
                <span class="text-xs sm:text-sm truncate">{{ item.label }}: {{ item.count }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="p-4 sm:p-5 bg-white rounded-lg shadow">
          <p class="text-slate-600 text-sm sm:text-base">
            <span v-if="userStore.isAdmin">Total tasks</span>
            <span v-else>Your tasks</span>
          </p>
          <p class="text-xl sm:text-2xl font-bold mt-1">{{ stats.total }}</p>
        </div>

      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { statsApi } from '@/api/stats'
import { useUserStore } from '@/stores/user'
import AppNavBar from '@/components/AppNavBar.vue'
import type { Stats } from '@/types'

const STATUS_ORDER = ['new', 'in_progress', 'done'] as const
const STATUS_LABELS: Record<string, string> = {
  new: 'New',
  in_progress: 'In Progress',
  done: 'Done',
}
const STATUS_COLORS: Record<string, string> = {
  new: '#3b82f6',
  in_progress: '#f59e0b',
  done: '#10b981',
}

const userStore = useUserStore()
const stats = ref<Stats | null>(null)

const chartData = computed(() => {
  if (!stats.value) return []
  return STATUS_ORDER.map((status) => ({
    status,
    label: STATUS_LABELS[status] ?? status,
    count: stats.value!.by_status[status] ?? 0,
    color: STATUS_COLORS[status] ?? '#94a3b8',
  }))
})

const donutStyle = computed(() => {
  if (!stats.value || stats.value.total === 0) return {}
  let acc = 0
  const parts = chartData.value
    .filter((d) => d.count > 0)
    .map((d) => {
      const pct = (d.count / stats.value!.total) * 100
      const start = acc
      acc += pct
      return `${d.color} ${start}% ${acc}%`
    })
  if (parts.length === 0) return {}
  return {
    background: `conic-gradient(${parts.join(', ')})`,
  }
})

async function loadStats() {
  const { data } = await statsApi.get()
  stats.value = data.data ?? null
}

onMounted(() => {
  loadStats()
})
</script>
