import { defineStore, acceptHMRUpdate } from 'pinia'
import logo from '@/assets/logo.png'

export const useAppStore = defineStore({
  id: 'App',
  state: () => ({
    setting: {
      title: '系统后台',
      sideIsCollapse: false,
      logo,
    },
  }),

  actions: {
    sideCollapse () {
      this.setting.sideIsCollapse = !this.setting.sideIsCollapse
    },
  },
})

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useAppStore, import.meta.hot))
}
