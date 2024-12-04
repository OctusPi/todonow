import auth from '@/stores/auth'
import utils from '@/utils/utils'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('@/views/LoginView.vue'),
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/LoginView.vue'),
    },
    {
      path: '/newuser',
      name: 'newuser',
      component: () => import('@/views/NewUserView.vue'),
    },
    {
      path: '/tasks',
      name: 'tasks',
      meta: {
        auth: true
      },
      component: () => import('@/views/TasksView.vue')
    },
    {
      path: '/forbidden',
      name: 'forbidden',
      component: () => import('../views/ForbiddenView.vue')
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'notfound',
      component: () => import('../views/NotFoundView.vue')
    }
  ],
})

router.beforeEach(async (to) => {
  if (to.meta?.auth) {
    utils.load(true)
    try {
      const isAuthenticated = await auth.is_authenticated(to.path)
      if (!isAuthenticated) {
        return '/'
      }
    } catch (e) {
      return e.response?.status === 403
        ? '/forbidden' : e.response?.status === 404
          ? '/notfound' : '/login'
    } finally {
      utils.load(false)
    }
  }
})

export default router
