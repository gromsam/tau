<template>
  <nav class="bg-white shadow p-4 flex justify-between items-center">
    <div class="flex gap-4 items-center">
      <router-link to="/">Tasks</router-link>
      <router-link to="/stats">Stats</router-link>
    </div>
      <span v-if="userName" class="text-sm">{{ userName }}</span>
    <button
      @click="handleLogout"
      class="text-slate-600 hover:text-slate-900"
    >
      Logout
    </button>
  </nav>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user'

const router = useRouter()
const userStore = useUserStore()

const { userName } = storeToRefs(userStore)

async function handleLogout() {
  await userStore.logout()
  router.push('/login')
}
</script>
