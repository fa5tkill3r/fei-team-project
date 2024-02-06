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

// https://medium.com/@pppped/compute-an-arbitrary-color-for-user-avatar-starting-from-his-username-with-javascript-cd0675943b66
export function stringToHslColor(
  str: string,
  saturation: number = 100,
  lightness: number = 73,
) {
  let hash = 0
  for (let i = 0; i < str.length; i++) {
    hash = str.charCodeAt(i) + ((hash << 5) - hash)
  }

  const hue = hash % 360

  return `hsl(${hue} ${saturation}% ${lightness}%)`
}
