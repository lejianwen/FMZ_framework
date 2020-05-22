import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true
  },

  {
    path: '/404',
    component: () => import('@/views/404'),
    hidden: true
  },

  {
    path: '/',
    component: Layout,
    redirect: '/dashboard',
    children: [{
      path: 'dashboard',
      name: 'Dashboard',
      component: () => import('@/views/dashboard/index'),
      meta: { title: '首页', icon: 'dashboard' }
    }]
  },

  {
    path: '/example',
    component: Layout,
    redirect: '/example/table',
    name: 'Example',
    meta: { title: 'Example', icon: 'example' },
    children: [
      {
        path: 'table',
        name: 'Table',
        component: () => import('@/views/table/index'),
        meta: { title: 'Table', icon: 'table' }
      },
      {
        path: 'tree',
        name: 'Tree',
        component: () => import('@/views/tree/index'),
        meta: { title: 'Tree', icon: 'tree' }
      },
      {
        path: 'base',
        name: 'baseTable',
        component: () => import('@/views/table/test'),
        meta: { title: 'baseTable', icon: 'table' }
      },
      {
        path: 'base2',
        name: 'baseTable2',
        component: () => import('@/views/table/test'),
        meta: { title: 'baseTable2', icon: 'table' }
      }
    ]
  },

  {
    path: '/form',
    component: Layout,
    children: [
      {
        path: 'index',
        name: 'Form',
        component: () => import('@/views/form/index'),
        meta: { title: 'Form', icon: 'form' }
      }
    ]
  },

  {
    path: '/nested',
    component: Layout,
    redirect: '/nested/menu1',
    name: 'Nested',
    meta: {
      title: 'Nested',
      icon: 'nested'
    },
    children: [
      {
        path: 'menu1',
        component: () => import('@/views/nested/menu1/index'), // Parent router-view
        name: 'Menu1',
        meta: { title: 'Menu1' },
        children: [
          {
            path: 'menu1-1',
            component: () => import('@/views/nested/menu1/menu1-1'),
            name: 'Menu1-1',
            meta: { title: 'Menu1-1' }
          },
          {
            path: 'menu1-2',
            component: () => import('@/views/nested/menu1/menu1-2'),
            name: 'Menu1-2',
            meta: { title: 'Menu1-2' },
            children: [
              {
                path: 'menu1-2-1',
                component: () => import('@/views/nested/menu1/menu1-2/menu1-2-1'),
                name: 'Menu1-2-1',
                meta: { title: 'Menu1-2-1' }
              },
              {
                path: 'menu1-2-2',
                component: () => import('@/views/nested/menu1/menu1-2/menu1-2-2'),
                name: 'Menu1-2-2',
                meta: { title: 'Menu1-2-2' }
              }
            ]
          },
          {
            path: 'menu1-3',
            component: () => import('@/views/nested/menu1/menu1-3'),
            name: 'Menu1-3',
            meta: { title: 'Menu1-3' }
          }
        ]
      },
      {
        path: 'menu2',
        component: () => import('@/views/nested/menu2/index'),
        meta: { title: 'menu2' }
      }
    ]
  },

  {
    path: 'external-link',
    component: Layout,
    children: [
      {
        path: 'https://panjiachen.github.io/vue-element-admin-site/#/',
        meta: { title: 'External Link', icon: 'link' }
      }
    ]
  }
]
/**
 * asyncRoutes
 * the routes that need to be dynamically loaded based on user roles
 */
export const asyncRoutes = [
  {
    path: '/admin',
    component: Layout,
    meta: { title: '系统列表', icon: 'lock', noCache: true },
    children: [
      {
        path: '/admin/list',
        component: () => import('@/views/admin/list'),
        name: 'adminList',
        meta: { title: '管理员列表', icon: 'user', noCache: true }
      },
      {
        path: '/admin/add',
        name: 'adminAdd',
        hidden: true,
        component: () => import('@/views/admin/add'),
        meta: { title: '添加管理员' }
      },
      {
        path: '/admin/detail/:id',
        name: 'adminDetail',
        hidden: true,
        component: () => import('@/views/admin/add'),
        meta: { title: '管理员详情' }
      },
      {
        path: '/admin/edit/:id',
        name: 'adminEdit',
        hidden: true,
        component: () => import('@/views/admin/add'),
        meta: { title: '编辑管理员' }
      },
      {
        path: '/adminRole/list',
        component: () => import('@/views/adminRole/list'),
        name: 'adminRoleList',
        meta: { title: '管理员角色列表', icon: 'peoples', noCache: true }
      },
      {
        path: '/adminRole/add',
        name: 'adminRoleAdd',
        hidden: true,
        component: () => import('@/views/adminRole/add'),
        meta: { title: '添加管理员角色' }
      },
      {
        path: '/adminRole/detail/:id',
        name: 'adminRoleDetail',
        hidden: true,
        component: () => import('@/views/adminRole/add'),
        meta: { title: '管理员角色详情' }
      },
      {
        path: '/adminRole/edit/:id',
        name: 'adminRoleEdit',
        hidden: true,
        component: () => import('@/views/adminRole/add'),
        meta: { title: '编辑管理员角色' }
      },
      {
        path: '/adminLog/list',
        component: () => import('@/views/adminLog/list'),
        name: 'adminLogList',
        meta: { title: '系统日志', icon: 'setting', noCache: true }
      },
      {
        path: '/config/list',
        component: () => import('@/views/config/list'),
        name: 'configList',
        meta: { title: '配置列表', icon: 'component', noCache: true }
      },
      {
        path: '/config/add',
        name: 'configAdd',
        hidden: true,
        component: () => import('@/views/config/add'),
        meta: { title: '添加配置' }
      },
      {
        path: '/config/detail/:id',
        name: 'configDetail',
        hidden: true,
        component: () => import('@/views/config/add'),
        meta: { title: '配置详情' }
      },
      {
        path: '/config/edit/:id',
        name: 'configEdit',
        hidden: true,
        component: () => import('@/views/config/add'),
        meta: { title: '编辑配置' }
      }
    ]
  }
]
export const errRoutes = [
  // 404 page must be placed at the end !!!
  { path: '*', redirect: '/404', hidden: true }
]
const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
