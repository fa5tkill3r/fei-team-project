import { Tag } from '@/types'

export function tryParseJSON<T>(json: string): T | undefined {
  try {
    return JSON.parse(json) as T
  } catch (err) {
    return undefined
  }
}

export function getStylesForTag(tag: Tag) {
  return {
    borderColor: tag.color,
    color: tag.color,
  }
}
