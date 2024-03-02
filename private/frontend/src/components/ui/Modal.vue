<template>
  <dialog class="modal" ref="dialog">
    <slot>
      <div class="modal-box flex flex-col"></div>
    </slot>
    <form method="dialog" class="modal-backdrop">
      <button>close</button>
    </form>
  </dialog>
</template>

<script lang="ts" setup>
import { onMounted, ref, watch } from 'vue'

const model = defineModel<boolean>()
const dialog = ref<HTMLDialogElement | null>(null)

onMounted(() => {
  if (!dialog.value) {
    console.warn('Dialog element not found')
    return
  }

  dialog.value.addEventListener('close', () => {
    model.value = false
  })
})

watch(
  model,
  (value) => {
    if (dialog.value) {
      if (value) {
        dialog.value.showModal()
      } else {
        dialog.value.close()
      }
    }
  },
  {
    immediate: true,
  },
)
</script>
