import { defineConfig } from 'astro/config'
import tailwind from '@astrojs/tailwind'
import svelte from "@astrojs/svelte"

// https://astro.build/config
export default defineConfig({
  site: 'http://localhost:8080',
  integrations: [tailwind(), svelte()]
})
