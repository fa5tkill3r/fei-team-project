import { nextTick, WritableComputedRef } from 'vue'
import { createI18n, I18n, I18nOptions } from 'vue-i18n'

export const SUPPORT_LOCALES = ['en', 'sk']

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
  // load locale messages with dynamic import
  const messages = await import(
    /* webpackChunkName: "locale-[request]" */ `./locales/${locale}.json`
  )

  // set locale and locale message
  i18n.global.setLocaleMessage(locale, messages.default)

  return nextTick()
}
