import api from './client'
import type { CatFact } from '@/types'

export const catFactApi = {
  get: () => api.get<CatFact>('/cat-fact'),
}
