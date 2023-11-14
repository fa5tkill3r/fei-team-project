/** @type {import('tailwindcss').Config} */
export default {
  content: ['./src/**/*.{astro,html}'],
  theme: {
    extend: {},
  },
  daisyui: {
    themes: [
      {
        'cirtligth': {
          'primary': '#2f327d',
          'secondary': '#e63946',
          'accent': '#c7cbe1',
          'neutral': '#1c1820',
          'base-100': '#ffffff',
          'info': '#4769f0',
          'success': '#12a178',
          'warning': '#f3b558',
          'error': '#e77381',
        },
        'cirtdark': {
          'primary': '#2f327d',
          'secondary': '#e63946',
          'accent': '#c7cbe1',
          'neutral': '#141b29',
          'base-100': '#25314b',
          'info': '#4769f0',
          'success': '#12a178',
          'warning': '#f3b558',
          'error': '#e77381',
        }
      }
    ],
    // styled: false,
    darkTheme: 'cirtdark'
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('daisyui')
  ],
}
