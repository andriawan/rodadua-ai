import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'

const routes: RouteRecordRaw[] = [
  // Auth routes
  {
    path: '/login',
    component: () => import('../layouts/AuthLayout.vue'),
    children: [
      {
        path: '',
        name: 'login',
        component: () => import('../pages/Login.vue'),
      },
    ],
  },
  {
    path: '/register',
    component: () => import('../layouts/AuthLayout.vue'),
    children: [
      {
        path: '',
        name: 'register',
        component: () => import('../pages/Register.vue'),
      },
    ],
  },

  // Main app routes
  {
    path: '/',
    component: () => import('../layouts/MainLayout.vue'),
    children: [
      {
        path: '',
        name: 'home',
        component: () => import('../pages/Home.vue'),
      },
      {
        path: 'motorcycles',
        name: 'motorcycle-list',
        component: () => import('../pages/List.vue'),
      },
      {
        path: 'motorcycles/add',
        name: 'motorcycle-add',
        component: () => import('../pages/MotorcycleAdd.vue'),
      },
      {
        path: 'motorcycles/:id',
        name: 'motorcycle-detail',
        component: () => import('../pages/Detail.vue'),
      },
      {
        path: 'motorcycles/:id/edit',
        name: 'motorcycle-edit',
        component: () => import('../pages/MotorcycleEdit.vue'),
      },
      {
        path: 'maintenance',
        name: 'maintenance',
        component: () => import('../pages/Maintenance.vue'),
      },
      {
        path: 'maintenance/add',
        name: 'maintenance-add',
        component: () => import('../pages/MaintenanceAdd.vue'),
      },
      {
        path: 'troubleshooting',
        name: 'troubleshooting',
        component: () => import('../pages/Troubleshooting.vue'),
      },
      {
        path: 'workshops',
        name: 'workshop-finder',
        component: () => import('../pages/WorkshopFinder.vue'),
      },
    ],
  },

  // Catch-all redirect
  {
    path: '/:pathMatch(.*)*',
    redirect: '/',
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 }
  },
})

// Navigation guard: redirect to login if not authenticated
router.beforeEach((to) => {
  const token = localStorage.getItem('auth_token')
  const publicRoutes = ['login', 'register']

  if (!token && !publicRoutes.includes(to.name as string)) {
    return { name: 'login' }
  }

  if (token && publicRoutes.includes(to.name as string)) {
    return { name: 'home' }
  }
})

export default router
