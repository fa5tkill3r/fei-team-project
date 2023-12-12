import { createDirectus, readItems, rest } from '@directus/sdk'
import type Post from '../interfaces/Post'

const directus = createDirectus(import.meta.env.API_URL).with(rest())

export const langMap: Record<string, string> = {
  en: 'en-US',
  sk: 'sk-SK',
}

export const reverseLangMap: Record<string, string> = Object.fromEntries(
  Object.entries(langMap).map(([k, v]) => [v, k])
)

export function getPostsByLanguage(lang: string): Promise<Post[]> {
  return directus
    .request(
      readItems('posts', {
        deep: {
          translations: {
            _filter: {
              languages_id: { _eq: langMap[lang] },
            },
          },
        },
        fields: ['*', { translations: ['*'] }],
        limit: 10,
      })
    )
    .catch(() => []) as Promise<Post[]>
}

export function getAllPosts() {
  return directus
    .request(
      readItems('posts', {
        fields: ['*', { translations: ['*'] }],
      })
    )
    .catch(() => [])
}
