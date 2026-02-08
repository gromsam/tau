import { createRouter, createWebHistory } from 'vue-router'
import LoginPage from '@/views/LoginPage.vue'
import TasksPage from '@/views/TasksPage.vue'
import StatsPage from '@/views/StatsPage.vue'
import { useUserStore } from '@/stores/user'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/login', name: 'login', component: LoginPage, meta: { guest: true } },
    { path: '/', name: 'tasks', component: TasksPage, meta: { requiresAuth: true } },
    { path: '/stats', name: 'stats', component: StatsPage, meta: { requiresAuth: true } },
  ],
})

router.beforeEach((to) => {
  const userStore = useUserStore()
  const hasAuth = userStore.isAuthenticated()
  if (to.meta.requiresAuth && !hasAuth) {
    return { name: 'login' }
  }
  if (to.meta.guest && hasAuth) {
    return { name: 'tasks' }
  }
})

export default router
