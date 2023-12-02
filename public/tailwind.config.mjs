/** @type {import('tailwindcss').Config} */
export default {
  content: ['./src/**/*.{astro,html,svelte}'],
  theme: {
    extend: {},
  },
  daisyui: {
    themes: [
      {
        'csirtligth': {
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
        'csirtdark': {
          'primary': '#767BF8',
          'secondary': '#B02833',
          'accent': '#63698B',
          'neutral': '#141b29',
          'base-100': '#262626',
          'info': '#4769f0',
          'success': '#12a178',
          'warning': '#f3b558',
          'error': '#e77381',
        }
      }
    ],
    // styled: false,
    darkTheme: 'csirtdark'
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('daisyui')
  ],
}
