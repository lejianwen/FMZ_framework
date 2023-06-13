import { createRouter, createWebHashHistory } from 'vue-router'

const constantRoutes = [
  {
    path: '/login',
    name: 'Login',
    meta: { title: '登录' },
    component: () => import('@/views/login/login.vue'),
  },

  {
    path: '/404',
    component: () => import('@/views/error-page/404.vue'),
    hidden: true,
  },
]
export const asyncRoutes = [
  {
    path: '/',
    name: 'Index',
    redirect: '/Home',
    meta: { title: '首页', icon: 'house' },
    component: () => import('@/layout/index.vue'),
    children: [
      {
        path: '/Home',
        name: 'Home',
        meta: { title: '首页', icon: 'house' },
        component: () => import('@/views/index/index.vue'),
      },
    ],
  },
  {
    path: '/admin',
    name: 'Admin',
    redirect: '/admin/index',
    meta: { title: '管理员', icon: 'user' },
    component: () => import('@/layout/index.vue'),
    children: [
      {
        path: 'index',
        name: 'AdminList',
        meta: { title: '管理员列表', icon: 'list' /*keepAlive: true*/ },
        component: () => import('@/views/admin/index.vue'),
      },
      {
        path: 'add',
        name: 'AdminAdd',
        meta: { title: '管理员添加', icon: 'plus'/*, hide: true */ },
        component: () => import('@/views/admin/edit.vue'),
      },
      {
        path: 'edit/:id',
        name: 'AdminEdit',
        meta: { title: '管理员编辑', hide: true },
        component: () => import('@/views/admin/edit.vue'),
      },

    ],
  },
]
export const lastRoutes = [
  { path: '/:catchAll(.*)', redirect: '/404', meta: { hide: true } },
]

export const router = createRouter({
  history: createWebHashHistory(),
  routes: constantRoutes,
})

