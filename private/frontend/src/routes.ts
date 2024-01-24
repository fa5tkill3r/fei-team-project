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
  // tasks
  {
    path: '/tasks',
    name: 'task-create',
    component: () => import('./pages/tasks/Create.vue'),
  },
  {
    path: '/tasks/:id',
    name: 'task-detail',
    component: () => import('./pages/tasks/Detail.vue'),
  },
  {
    path: '/tasks/:id/edit',
    name: 'task-edit',
    component: () => import('./pages/tasks/Edit.vue'),
  },
  // team
  {
    path: '/team/create',
    name: 'team-create',
    component: () => import('./pages/team/Create.vue'),
  },
]

// TODO: auth guard

export default routes
