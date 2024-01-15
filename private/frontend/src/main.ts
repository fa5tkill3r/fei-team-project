import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import { createPinia } from 'pinia'
import { useAuthStore } from './stores/auth'
import { SUPPORT_LOCALES, loadLocaleMessages, setupI18n } from './i18n'
import routes from './routes.ts'
import App from './App.vue'

import './style.css'

const router = createRouter({
  history: createWebHistory(),
  routes,
})
const i18n = setupI18n({ legacy: false })
const app = createApp(App)
const pinia = createPinia()

app.use(i18n)
app.use(pinia)
app.use(router)

const auth = useAuthStore()

function loadInitialLocale() {
  let locale = 'sk'
  const lang = navigator.language.substring(0, 2).toLowerCase()

  if (SUPPORT_LOCALES.includes(lang)) {
    locale = lang
  }

  return loadLocaleMessages(i18n, locale)
}

auth
  .login()
  .catch(() => {})
  // TODO: load locale from user settings
  .then(loadInitialLocale)
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
