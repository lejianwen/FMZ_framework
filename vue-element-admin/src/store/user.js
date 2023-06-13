import { defineStore, acceptHMRUpdate } from 'pinia'
import { info, login } from '@/api/user'
import { setToken, removeToken } from '@/utils/auth'
import { useRouteStore } from '@/store/router'

export const useUserStore = defineStore({
  id: 'user',
  state: () => ({
    nickname: '',
    token: '',
    role: null,
    avatar: '',
  }),

  actions: {
    logout () {
      removeToken()
      this.$reset()
    },

    async login (form) {
      const res = await login(form).catch(_ => false)
      if (res) {
        const userData = res.data
        setToken(userData.token)
        this.$patch({
          ...userData,
        })
        return userData
      } else {
        return false
      }
    },
    async info () {
      const res = await info().catch(_ => false)
      if (res) {
        const userData = res.data
        setToken(userData.token)
        this.$patch({
          ...userData,
        })
        // console.log(userData)
        return userData
      }
      return false
    },
  },
})

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useUserStore, import.meta.hot))
}
