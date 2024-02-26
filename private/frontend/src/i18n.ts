import { nextTick, WritableComputedRef } from 'vue'
import { createI18n, I18n, I18nOptions } from 'vue-i18n'
// @ts-ignore
export { default as defaultMessages } from './locales/en.js'

export const SUPPORTED_LOCALES = ['en', 'sk']

export function setupI18n(options: I18nOptions) {
  const i18n = createI18n(options)

  setI18nLanguage(i18n, options.locale ?? 'sk')

  return i18n
}

export function setI18nLanguage(i18n: I18n, locale: string) {
  ;(i18n.global.locale as WritableComputedRef<string>).value = locale

  document.querySelector('html')!.setAttribute('lang', locale)
}

export async function loadLocaleMessages(i18n: I18n, locale: string) {
  if (locale === 'en') {
    return
  }

  // load locale messages with dynamic import
  const messages = await import(
    /* webpackChunkName: "locale-[request]" */ `./locales/${locale}.js`
  )

  i18n.global.setLocaleMessage(locale, messages.default)

  return nextTick()
}

export const datetimeFormats = {
  en: {
    short: {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
    },
  },
  sk: {
    short: {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
    },
  },
}

export const pluralRules = {
  sk: (choice: number) => {
    if (choice === 1) {
      return 0
    }
    if (choice >= 2 && choice <= 4) {
      return 1
    }
    return 2
  },
}
