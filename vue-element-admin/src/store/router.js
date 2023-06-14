import { acceptHMRUpdate, defineStore } from 'pinia'
import { asyncRoutes, lastRoutes, router } from '@/router'

function filterRoute (routes, enableNames) {
  return routes.filter(route => {
    if (route.children && route.children.length) {
      return enableNames.includes(route.name) || route.children.some(r => enableNames.includes(r.name))
    } else {
      return enableNames.includes(route.name)
    }
  }).map(route => {
    if (route.children && route.children.length) {
      return {
        ...route,
        children: filterRoute(route.children, enableNames),
      }
    } else {
      return { ...route }
    }
  })
}

export const useRouteStore = defineStore({
  id: 'router',
  state: () => ({
    routes: [],
    activeRoute: '',
    loaded: 0,
    keepAlive: [],
  }),
  actions: {
    addRoutes (accessRouteNames) {
      if (accessRouteNames === '*') {
        this.routes = asyncRoutes.map(r => { return { ...r } })
      } else {
        this.routes = filterRoute(asyncRoutes, accessRouteNames)
      }
      this.routes.map(route => {
        router.addRoute(route)
      })
      lastRoutes.map(route => {
        router.addRoute(route)
      })
      this.addKeepAlive(this.routes)
    },
    addKeepAlive (route) {
      if (route instanceof Array) {
        route.map(r => {
          this.addKeepAlive(r)
        })
      } else if (route.children && route.children.length) {
        this.addKeepAlive(route.children)
      } else if (route.meta?.keepAlive && !this.keepAlive.includes(route.name)) {
        this.keepAlive.push(route.name)
      }
    },

  },
})

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useRouteStore, import.meta.hot))
}
