import { defineAstroI18nConfig } from 'astro-i18n'

export default defineAstroI18nConfig({
  primaryLocale: 'sk', // default app locale
  secondaryLocales: ['en'], // other supported locales
  fallbackLocale: 'en', // fallback locale (on missing translation)
  trailingSlash: 'never', // "never" or "always"
  run: 'server', // "client+server" or "server"
  showPrimaryLocale: false, // "/en/about" vs "/about"
  translationLoadingRules: [], // per page group loading
  translationDirectory: {}, // translation directory names
  translations: {}, // { [translation_group1]: { [locale1]: {}, ... } }
  routes: {}, // { [secondary_locale1]: { about: "about-translated", ... } }
})
