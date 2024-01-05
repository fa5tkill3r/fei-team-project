import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import { createPinia } from 'pinia'
import { useAuthStore } from './stores/auth'
import routes from './routes.ts'
import App from './App.vue'
import './style.css'

const router = createRouter({
  history: createWebHistory(),
  routes,
})

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)

const auth = useAuthStore()

auth
  .login()
  .catch(() => {})
  .finally(() => {
    app.mount('#app')
  })

router.beforeEach((to, _, next) => {
  if (!auth.user && !to.meta.guest) {
    const redirect = to.fullPath === '/' ? undefined : to.fullPath

    next({
      name: 'login',
      query: { redirect },
    })
  } else {
    next()
  }
})
