<template>
  <button>
    <span v-if="props.loading" class="loading loading-spinner"></span>
    <span v-else>
      <slot></slot>
    </span>
  </button>
</template>

<script setup lang="ts">
const props = withDefaults(defineProps<{ loading: boolean }>(), {
  loading: false,
})
</script>
