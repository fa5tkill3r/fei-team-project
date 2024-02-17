import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import { createPinia } from 'pinia'
import { useAuthStore } from './stores/auth'
import { useTeamStore } from './stores/team'
import {
  SUPPORTED_LOCALES,
  datetimeFormats,
  loadLocaleMessages,
  setI18nLanguage,
  setupI18n,
} from './i18n'
import routes from './routes.ts'
import App from './App.vue'

import './style.css'

const router = createRouter({
  history: createWebHistory(),
  routes,
})
const i18n = setupI18n({
  legacy: false,
  fallbackLocale: 'en',
  // @ts-ignore
  datetimeFormats,
})
const app = createApp(App)
const pinia = createPinia()

app.use(i18n)
app.use(pinia)
app.use(router)

const auth = useAuthStore()
const team = useTeamStore()

function loadInitialLocale() {
  let locale = 'sk'
  const lang = navigator.language.substring(0, 2).toLowerCase()

  if (SUPPORTED_LOCALES.includes(lang)) {
    locale = lang
  }

  return loadLocaleMessages(i18n, locale).then(() =>
    setI18nLanguage(i18n, locale),
  )
}

const promise = auth
  .restore()
  .then(team.loadTeams)
  .catch(() => {})
  // TODO: load locale from user settings
  .then(loadInitialLocale)
  .finally(() => {
    app.mount('#app')
  })

router.beforeEach(async (to, _, next) => {
  await promise

  if (!auth.user && !to.meta.guest) {
    const redirect = to.fullPath === '/' ? undefined : to.fullPath

    next({
      name: 'login',
      query: { redirect },
    })
  }
  if (auth.user && to.meta.guest) {
    next({ name: 'home' })
  } else {
    next()
  }
})
