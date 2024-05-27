<script setup lang="ts">
import { useFormatDistance } from '@/composables/useFormatDistance.ts'
import { Task } from '@/types'
import { EllipsisHorizontalIcon } from '@heroicons/vue/24/solid'
import { computed, ref, watch } from 'vue'
import UserAvatar from './ui/UserAvatar.vue'
import { useAuthStore } from '@/stores/auth.ts'

const props = defineProps<{
  comment: any
  task: Task
}>()

const formatDistance = useFormatDistance()
const comment = computed(() => props.comment)
const deleteLoading = ref(false)
const editLoading = ref(false)
const edit = ref<boolean>(false)
const editText = ref(comment.value.comment)
const isTaskClosed = computed(() => props.task.status === 'closed')
const authStore = useAuthStore()
const loadingShowInReport = ref(false)

const emit = defineEmits<{
  editComment: [{ id: number; text: string }]
  deleteComment: [id: number]
}>()

watch(
  () => props.comment.comment,
  () => {
    editText.value = comment.value.comment
    edit.value = false
    editLoading.value = false
  },
)

function deleteComment() {
  deleteLoading.value = true
  emit('deleteComment', comment.value.id)
}

function editComment() {
  editLoading.value = true
  emit('editComment', { id: comment.value.id, text: editText.value })
}

function showInReport() {
  loadingShowInReport.value = true

  authStore.client
    .put({
        show_in_report: !comment.value.show_in_report
      },
      `task/${props.task.id}/comments/${comment.value.id}`)
    .json()
    .then(() => {
      comment.value.show_in_report = !comment.value.show_in_report
    }).finally(() => {
      loadingShowInReport.value = false
    })
}
</script>

<template v-if="comment">
  <div class="flex gap-2">
    <div>
      <UserAvatar :user="comment.user" size="md" />
    </div>

    <div class="rounded-lg border border-base-content/10 w-full">
      <div
        class="border-b border-base-content/10 px-4 py-2 text-sm label-text flex justify-between items-center"
      >
        <span>
          <b>{{ `${comment.user.first_name} ${comment.user.last_name}` }}</b>
          {{
            $t('task.commented_at', {
              distance: formatDistance(comment.created_at),
            })
          }}
        </span>
        <div class="flex items-center gap-2">
          <button
            :class='{
              "btn btn-sm btn-outline btn-warning": !comment.show_in_report,
              "btn btn-sm btn-info": comment.show_in_report,
            }'
            @click="showInReport"
            :disabled="loadingShowInReport"
          >
            <span v-if="loadingShowInReport" class="loading loading-sm"></span>

            <span v-if="!comment.show_in_report">{{ $t('task.comment.not_in_report') }}</span>
            <span v-else>{{ $t('task.comment.in_report') }}</span>
          </button>
          <div class="dropdown dropdown-end">
            <button class="btn btn-xs btn-ghost">
              <EllipsisHorizontalIcon class="w-5 h-5" />
            </button>
            <ul
              tabindex="0"
              class="p-2 shadow menu dropdown-content bg-base-100 rounded-box w-52"
            >
              <li :class="{ disabled: isTaskClosed }">
                <button :disabled="isTaskClosed || edit" @click="edit = !edit">
                  {{ $t('edit') }}
                </button>
              </li>
              <li :class="{ disabled: isTaskClosed }">
                <button
                  :disabled="isTaskClosed"
                  :class="deleteLoading ? 'ml-3 loading loading-sm' : ''"
                  @click="deleteComment"
                >
                  {{ $t('delete') }}
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div
        class="prose max-w-none bg-base-300/30 px-4 pt-2 pb-2.5 rounded-b-lg text-base-content"
      >
        <span v-if="!edit">{{ comment.comment }}</span>

        <div v-else>
          <textarea
            class="p-2 textarea textarea-bordered w-full"
            :placeholder="$t('task.comment.placeholder')"
            v-model="editText"
            v-on:keydown.ctrl.enter="editComment"
          ></textarea>
          <div class="flex justify-end gap-2 mt-2">
            <button
              class="btn btn-sm btn-error"
              :disabled="false"
              @click="edit = false"
            >
              {{ $t('cancel') }}
            </button>
            <button
              class="btn btn-sm btn-success"
              :disabled="
                editLoading || editText === comment.comment || editText === ''
              "
              @click="editComment"
            >
              <span v-if="editLoading" class="loading loading-spinner"></span>
              {{ $t('task.comment.update') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
./ui/UserAvatar.vue
