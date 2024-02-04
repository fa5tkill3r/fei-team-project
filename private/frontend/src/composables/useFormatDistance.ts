import { useI18n } from 'vue-i18n'
import { intlFormatDistance } from 'date-fns'

export function useFormatDistance() {
  const i18n = useI18n()

  function formatDistanceToNow(date: string | Date) {
    return intlFormatDistance(date, new Date(), {
      numeric: 'auto',
      locale: i18n.locale.value,
    })
  }

  return formatDistanceToNow
}
