import Empty from './layouts/Empty.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('./pages/Index.vue'),
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('./pages/auth/Login.vue'),
    meta: {
      layout: Empty,
      guest: true,
    },
  },
]

// TODO: auth guard

export default routes
