import Empty from './layouts/Empty.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('./pages/Index.vue'),
  },
  // auth
  {
    path: '/register',
    name: 'register',
    component: () => import('./pages/auth/Register.vue'),
    meta: {
      layout: Empty,
      guest: true,
    },
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('./pages/auth/Login.vue'),
    meta: {
      layout: Empty,
      guest: true,
    },
  },
]

// TODO: auth guard

export default routes
