import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { authApi } from '@/api/auth'
import { getAuthToken, removeAuthToken, setAuthToken } from '@/utils/cookie'
import type { User } from '@/types'
import { AxiosResponse } from "axios";

export const useUserStore = defineStore('user', () => {
  const token = ref<string | null>(getAuthToken())
  const user = ref<User | null>(null)

  const isAuthenticated = () => Boolean(token.value)
  const isAdmin = computed(() => user.value?.is_admin === true)
  const userName = computed(() => user.value?.name ?? '')

  function setUser(newUser: User | null) {
    user.value = newUser
  }

  function setToken(newToken: string | null) {
    token.value = newToken
    if (newToken) {
      setAuthToken(newToken)
    } else {
      removeAuthToken()
    }
  }

  async function loadUser() {
    if (!token.value) return
    try {
        const response: AxiosResponse = await authApi.user()
        setUser(response.data)
    } catch {
      setUser(null)
    }
  }

  async function login(email: string, password: string) {
    const { data } = await authApi.login(email, password)
    setToken(data.token)
    setUser(data.user)
  }

  async function logout() {
    try {
      await authApi.logout()
    } catch {
      // ignore
    }
    setToken(null)
    setUser(null)
  }

  return {
    user,
    token,
    isAuthenticated,
    isAdmin,
    userName,
    setUser,
    setToken,
    loadUser,
    login,
    logout,
  }
})
