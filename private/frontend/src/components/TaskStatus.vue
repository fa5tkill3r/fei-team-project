<template>
  <span
    class="badge h-6"
    :class="{
      'badge-warning': task.status === 'new',
      'badge-success': task.status === 'in_progress',
      'badge-primary': task.status === 'closed',
    }"
  >
    {{ $t('task.status.' + task.status) }}
  </span>
</template>

<script setup lang="ts">
import { Task } from '@/types'
import { toRefs } from 'vue'

const props = defineProps<{ task: Task }>()
const { task } = toRefs(props)
</script>
