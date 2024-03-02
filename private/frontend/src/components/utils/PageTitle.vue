<template></template>

<script setup lang="ts">
import { onUnmounted, useAttrs, watchEffect } from 'vue'
import { useI18n } from 'vue-i18n'

const props = defineProps<{ text: string }>()
const attrs = useAttrs()
const { t } = useI18n()

watchEffect(
  () => {
    document.title = props.text ? `${t(props.text, attrs)} · CSIRT` : 'CSIRT'
  },
  {
    flush: 'post',
  },
)

onUnmounted(() => {
  document.title = 'CSIRT'
})
</script>
