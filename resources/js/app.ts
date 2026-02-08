import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import { setAuthHandlers } from '@/api/client'
import { useUserStore } from '@/stores/user'
import '../css/app.css'

const pinia = createPinia()
const app = createApp(App)
app.use(pinia).use(router)

setAuthHandlers(
  () => useUserStore().token,
  () => {
    useUserStore().logout()
    router.push('/login')
  }
)

app.mount('#app')
