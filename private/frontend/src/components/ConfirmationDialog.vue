<template>
  <Modal v-model="model">
    <div class="modal-box">
      <h1 class="text-xl font-bold mb-3">
        {{ $t('confirmation') }}
      </h1>

      <slot></slot>

      <div class="flex justify-end mt-4">
        <button
          type="button"
          class="btn mr-2"
          :class="{
            [`btn-${props.colorCancel}`]: true,
          }"
          @click="model = false"
        >
          {{ $t('no') }}
        </button>
        <LoadingButton
          type="submit"
          class="btn"
          :class="{
            [`btn-${props.colorConfirm}`]: true,
          }"
          :loading="confirmLoading"
          @click="confirm"
        >
          {{ $t('yes') }}
        </LoadingButton>
      </div>
    </div>
  </Modal>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import LoadingButton from './ui/LoadingButton.vue'
import Modal from './ui/Modal.vue'

const model = defineModel<boolean>()
const props = defineProps({
  colorConfirm: {
    default: 'error',
  },
  colorCancel: {
    default: 'neutral',
  },
})
const emit = defineEmits<{
  confirm: []
}>()
const confirmLoading = ref(false)

watch(
  () => model.value,
  (value) => {
    if (!value) {
      confirmLoading.value = false
    }
  },
)

function confirm() {
  confirmLoading.value = true
  emit('confirm')
}
</script>
