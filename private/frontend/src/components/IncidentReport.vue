<script setup lang="ts">
import { useFormatDistance } from '@/composables/useFormatDistance.ts'
import { IncidentChronologically } from '@/types'
import { EllipsisHorizontalIcon } from '@heroicons/vue/24/solid'
import { computed, ref } from 'vue'

const props = defineProps<{
  report: IncidentChronologically
}>()

const formatDistance = useFormatDistance()
const report = computed(() => props.report)
const deleteLoading = ref(false)
// const editLoading = ref(false) // TODO: Implement edit functionality
const edit = ref<boolean>(false)

const emit = defineEmits<{
  edit: [{ id: number; text: string }]
  delete: [id: number]
}>()

function deleteReport() {
  deleteLoading.value = true
  emit('delete', report.value.id!!)
}

//function editComment() { TODO: Implement edit functionality
  // editLoading.value = true
  // emit('editComment', { id: report.value.id, text: editText.value })
//}
</script>

<template v-if="report">
  <div class="flex gap-2">
    <div class="rounded-lg border border-base-content/10 w-full">
      <div
        class="border-b border-base-content/10 px-4 py-2 text-sm label-text flex justify-between items-center"
      >
        <span>
          {{
            $t('incidents.recorded_at', {
              distance: formatDistance(report.date),
            })
          }}
        </span>
        <div class="dropdown dropdown-end">
          <button class="btn btn-xs btn-ghost">
            <EllipsisHorizontalIcon class="w-5 h-5" />
          </button>
          <ul
            tabindex="0"
            class="p-2 shadow menu dropdown-content bg-base-100 rounded-box w-52"
          >
            <li>
              <button :disabled="edit" @click="edit = !edit">
                {{ $t('edit') }}
              </button>
            </li>
            <li>
              <button
                :class="deleteLoading ? 'ml-3 loading loading-sm' : ''"
                @click="deleteReport"
              >
                {{ $t('delete') }}
              </button>
            </li>
          </ul>
        </div>
      </div>

      <div
        class="prose max-w-none bg-base-300/30 px-4 pt-2 pb-2.5 rounded-b-lg text-base-content"
      >
        <span v-if="!edit">{{ report.description }}</span>
      </div>
    </div>
  </div>
</template>
