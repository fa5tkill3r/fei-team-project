/** @type {import('tailwindcss').Config} */
export default {
  content: ['./src/**/*.{astro,html}'],
  theme: {
    extend: {},
  },
  daisyui: {
    themes: ['emerald'],
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('daisyui')
  ],
}
