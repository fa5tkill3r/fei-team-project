export function tryParseJSON<T>(json: string): T | undefined {
  try {
    return JSON.parse(json) as T
  } catch (err) {
    return undefined
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

/**
 * Converts an HSL color value to RGB. Conversion formula
 * adapted from https://en.wikipedia.org/wiki/HSL_color_space.
 * Assumes h, s, and l are contained in the set [0, 1] and
 * returns r, g, and b in the set [0, 255].
 *
 * https://stackoverflow.com/a/9493060
 *
 * @param   {number}  h       The hue
 * @param   {number}  s       The saturation
 * @param   {number}  l       The lightness
 * @return  {Array}           The RGB representation
 */
export function hslToRgb(h: number, s: number, l: number) {
  let r, g, b

  h /= 360
  s /= 100
  l /= 100

  if (s === 0) {
    r = g = b = l // achromatic
  } else {
    const q = l < 0.5 ? l * (1 + s) : l + s - l * s
    const p = 2 * l - q
    r = hueToRgb(p, q, h + 1 / 3)
    g = hueToRgb(p, q, h)
    b = hueToRgb(p, q, h - 1 / 3)
  }

  return [toByte(r), toByte(g), toByte(b)]
}

function toByte(num: number) {
  return Math.min(255, Math.floor(256 * num))
}

function hueToRgb(p: number, q: number, t: number) {
  if (t < 0) t += 1
  if (t > 1) t -= 1
  if (t < 1 / 6) return p + (q - p) * 6 * t
  if (t < 1 / 2) return q
  if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6
  return p
}

export function rgbToHex(color: number[]) {
  return (
    '#' +
    color[0].toString(16).padStart(2, '0') +
    color[1].toString(16).padStart(2, '0') +
    color[2].toString(16).padStart(2, '0')
  )
}

export function hexToRgb(hex: string) {
  const temp = hex.substring(1)
  const r = parseInt(temp.substring(0, 2), 16)
  const g = parseInt(temp.substring(2, 4), 16)
  const b = parseInt(temp.substring(4, 6), 16)

  return [r, g, b]
}

/**
 * Converts an RGB color value to HSL. Conversion formula
 * adapted from http://en.wikipedia.org/wiki/HSL_color_space.
 * Assumes r, g, and b are contained in the set [0, 255] and
 * returns h, s, and l in the set [0, 1].
 *
 * @param   {number}  r       The red color value
 * @param   {number}  g       The green color value
 * @param   {number}  b       The blue color value
 * @return  {Array}           The HSL representation
 */
export function rgbToHsl(r: number, g: number, b: number) {
  r /= 255
  g /= 255
  b /= 255

  const vmax = Math.max(r, g, b)
  const vmin = Math.min(r, g, b)
  let h = 0
  let s = 0
  const l = (vmax + vmin) / 2

  if (vmax === vmin) {
    return [0, 0, l] // achromatic
  }

  const d = vmax - vmin
  s = l > 0.5 ? d / (2 - vmax - vmin) : d / (vmax + vmin)
  if (vmax === r) h = (g - b) / d + (g < b ? 6 : 0)
  if (vmax === g) h = (b - r) / d + 2
  if (vmax === b) h = (r - g) / d + 4
  h /= 6

  return [h * 360, s * 100, l * 100]
}
