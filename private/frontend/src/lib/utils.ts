export function tryParseJSON<T>(json: string): T | undefined {
  try {
    return JSON.parse(json) as T
  } catch (err) {
    return undefined
  }
}
