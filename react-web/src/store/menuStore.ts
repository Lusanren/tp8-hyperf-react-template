import { create } from 'zustand'
import { MenuItem } from '@/types/api'

interface MenuState {
  menus: MenuItem[]
  isCollapsed: boolean
  setMenus: (menus: MenuItem[]) => void
  toggleCollapse: () => void
}

export const useMenuStore = create<MenuState>((set) => ({
  menus: [],
  isCollapsed: false,
  setMenus: (menus: MenuItem[]) => set({ menus }),
  toggleCollapse: () => set((state) => ({ isCollapsed: !state.isCollapsed })),
}))
