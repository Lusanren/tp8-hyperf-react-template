import { create } from 'zustand'
import { UserInfo } from '@/types/api'

interface UserState {
  token: string | null
  userInfo: UserInfo | null
  setLogin: (token: string, userInfo: UserInfo) => void
  logout: () => void
}

export const useUserStore = create<UserState>((set) => ({
  token: localStorage.getItem('token'),
  userInfo: localStorage.getItem('userInfo')
    ? JSON.parse(localStorage.getItem('userInfo')!)
    : null,

  setLogin: (token: string, userInfo: UserInfo) => {
    localStorage.setItem('token', token)
    localStorage.setItem('userInfo', JSON.stringify(userInfo))
    set({ token, userInfo })
  },

  logout: () => {
    localStorage.removeItem('token')
    localStorage.removeItem('userInfo')
    set({ token: null, userInfo: null })
  },
}))
