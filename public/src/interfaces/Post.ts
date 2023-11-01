import type Translation from './Translation'

export default interface Post {
  id: number
  slug: string
  translations: Translation[]
}
