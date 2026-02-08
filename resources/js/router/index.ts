import { createRouter, createWebHistory } from 'vue-router'
import LoginPage from '@/views/LoginPage.vue'
import TasksPage from '@/views/TasksPage.vue'
import StatsPage from '@/views/StatsPage.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/login', name: 'login', component: LoginPage, meta: { guest: true } },
    { path: '/', name: 'tasks', component: TasksPage, meta: { requiresAuth: true } },
    { path: '/stats', name: 'stats', component: StatsPage, meta: { requiresAuth: true } },
  ],
})

router.beforeEach((to) => {
  const token = localStorage.getItem('token')
  if (to.meta.requiresAuth && !token) {
    return { name: 'login' }
  }
  if (to.meta.guest && token) {
    return { name: 'tasks' }
  }
})

export default router
