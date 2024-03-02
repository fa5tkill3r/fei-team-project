<template>
  <div class="badge h-6" :style="styles">
    <span>{{ name }}</span>
  </div>
</template>

<script setup lang="ts">
import { hexToRgb, rgbToHsl } from '@/lib/utils'
import { computed, defineProps, toRefs } from 'vue'

const props = defineProps<{
  name: string
  color: string | number[]
}>()

const { color, name } = toRefs(props)

const styles = computed(() => {
  if (typeof color.value === 'string') {
    const [r, g, b] = hexToRgb(color.value)
    const [h, s, l] = rgbToHsl(r, g, b)
    const hsl = `${h}, ${s}%, ${l}%`

    return {
      borderColor: `hsla(${hsl}, 0.3)`,
      color: `hsla(${hsl}, 1)`,
      backgroundColor: `hsla(${hsl}, 0.18)`,
    }
  }

  const [h, s, l] = color.value
  const hsl = `${h}, ${s}%, ${l}%`

  return {
    borderColor: `hsla(${hsl}, 0.3)`,
    color: `hsla(${hsl}, 1)`,
    backgroundColor: `hsla(${hsl}, 0.18)`,
  }
})
</script>
