<script setup lang="ts">
import { MenuItem } from '@headlessui/vue'

const props = defineProps<{
  disabled?: boolean
}>()
</script>

<template>
  <MenuItem as="li" :class="{ disabled: props.disabled }">
    <button :class="{ 'cursor-not-allowed': props.disabled }">
      <slot></slot>
    </button>
  </MenuItem>
</template>
