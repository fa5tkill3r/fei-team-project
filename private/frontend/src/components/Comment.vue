<script setup lang='ts'>

import { EllipsisHorizontalIcon } from '@heroicons/vue/24/solid'
import { computed, ref, watch } from 'vue'
import { useAuthStore } from '@/stores/auth.ts'
import { useFormatDistance } from '@/composables/useFormatDistance.ts'

const props = defineProps<{
  comment: any
}>()


const formatDistance = useFormatDistance()
const comment = computed(() => props.comment)
const deleteLoading = ref(false)
const editLoading = ref(false)
const edit = ref<boolean>(false)
const editText = ref(comment.value.comment)

const emit = defineEmits<{
  deleteComment: [id: number],
  editComment: [id: number, text: string]
}>()

watch(() => props.comment.comment,
  () => {
  editText.value = comment.value.comment
  edit.value = false
  editLoading.value = false
})

function deleteComment() {
  deleteLoading.value = true
  emit('deleteComment', comment.value.id)
}

function editComment() {
  editLoading.value = true
  emit('editComment', { id: comment.value.id, text: editText.value })
}

</script>

<template v-if='comment'>
  <div class='flex gap-2'>
    <div>
      <div class='avatar placeholder'>
        <div
          class='w-10 rounded-full bg-neutral text-neutral-content'
        >
          <span>{{ comment.user.first_name[0] + comment.user.last_name[0] }}</span>
        </div>
      </div>
    </div>

    <div class='rounded-lg border border-base-content/10 w-full'>
      <div
        class='border-b border-base-content/10 px-4 py-2 text-sm label-text flex justify-between items-center'
      >
        <span>
          <b>{{ `${comment.user.first_name} ${comment.user.last_name}` }}</b> {{
            $t('task.comment.commented_at', {
              distance: formatDistance(comment.created_at),
            })
          }}
        </span>
        <div class='dropdown dropdown-end'>
          <button class='btn btn-xs btn-ghost'>
            <EllipsisHorizontalIcon class='w-5 h-5' />
          </button>
          <ul
            tabindex='0'
            class='p-2 shadow menu dropdown-content bg-base-100 rounded-box w-52'
          >
            <li>
              <button
                :disabled='edit'
                @click='edit = !edit'
              >
                {{ $t('task.comment.edit') }}
              </button>
            </li>
            <li>
              <button
                :class='deleteLoading ? "ml-3 loading loading-sm" : ""'
                @click='deleteComment'
              >
                {{ $t('task.comment.delete') }}
              </button>
            </li>
          </ul>
        </div>
      </div>

      <div
        class='prose max-w-none bg-base-300/30 px-4 pt-2 pb-2.5 rounded-b-lg text-base-content'
      >
        <span v-if='!edit'>{{ comment.comment }}</span>

        <div v-else>
          <textarea class='p-2 textarea textarea-bordered w-full'
                    :placeholder="$t('task.comment.placeholder')"
                    v-model='editText'
                    v-on:keydown.ctrl.enter='editComment'
          ></textarea>
          <div class='flex justify-end gap-2 mt-2'>
            <button class='btn btn-sm btn-error'
                    :disabled='false'
                    @click='edit = false'>{{ $t('cancel') }}
            </button>
            <button class='btn btn-sm btn-success'
                    :disabled='editLoading || editText === comment.comment || editText === ""'
                    @click='editComment'
            >
              <span v-if='editLoading' class='loading loading-spinner'></span>
              {{ $t('task.comment.update') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
