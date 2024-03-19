import { RouteLocationNormalizedLoaded } from 'vue-router'
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
    component: () => import('./pages/tasks/CreateEdit.vue'),
  },
  {
    path: '/tasks/:id/edit',
    name: 'task-edit',
    component: () => import('./pages/tasks/CreateEdit.vue'),
    props: (route: RouteLocationNormalizedLoaded) => ({
      id: Number(route.params.id),
      edit: true,
    }),
  },
  {
    path: '/tasks/:id',
    name: 'task-detail',
    component: () => import('./pages/tasks/Detail.vue'),
  },
  // tags
  {
    path: '/tags',
    name: 'tags',
    component: () => import('./pages/tags/Index.vue'),
  },
  // team
  {
    path: '/team/create',
    name: 'team-create',
    component: () => import('./pages/team/Create.vue'),
  },
  // user
  {
    path: '/user/settings',
    name: 'user-settings',
    component: () => import('./pages/user/Settings.vue'),
  },
  // incidents
  {
    path: '/incidents',
    name: 'incidents',
    component: () => import('./pages/incidents/Index.vue'),
  },
  // theses
  {
    path: '/theses',
    name: 'theses',
    component: () => import('./pages/theses/Index.vue'),
  },
  // library
  {
    path: '/library',
    name: 'library',
    component: () => import('./pages/library/Index.vue'),
  },
  // admin
  {
    path: '/admin',
    name: 'admin',
    component: () => import('./pages/admin/Index.vue'),
  },
  // admin add role
  {
    path: '/admin/role/add',
    name: 'role-add',
    component: () => import('./pages/admin/Role.vue'),
  },
  // admin edit role
  {
    path: '/admin/role/:id',
    name: 'role-edit',
    component: () => import('./pages/admin/Role.vue'),
  },
]

export default routes
