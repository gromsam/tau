import axios from 'axios'

let getToken: (() => string | null) | null = null
let onUnauthorized: (() => void) | null = null

export function setAuthHandlers(
  tokenGetter: () => string | null,
  unauthorizedCallback: () => void
) {
  getToken = tokenGetter
  onUnauthorized = unauthorizedCallback
}

const api = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
})

api.interceptors.request.use((config) => {
  const token = getToken?.() ?? null
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401 && onUnauthorized) {
      onUnauthorized()
    }
    return Promise.reject(error)
  }
)

export default api
