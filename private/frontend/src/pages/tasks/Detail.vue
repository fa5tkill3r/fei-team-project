<template>
  <div class="max-w-7xl mx-auto">
    <div v-if="initialLoading" class="flex justify-center items-center h-96">
      <div class="loading loading-spinner"></div>
    </div>

    <div v-else-if="task" class="grid grid-cols-12 gap-x-4">
      <div class="col-span-full flex justify-between">
        <div>
          <h1 class="text-2xl font-bold text-base-content">
            {{ task.name }}
            <span class="text-base-content/65">#{{ task.id }}</span>
          </h1>
          <div class="label-text">
            <span class="font-bold mr-1">
              {{ task.created_by.first_name }}
              {{ task.created_by.last_name }}
            </span>
            <span>
              {{
                $t('task.created_at', {
                  distance: formatDistance(task.created_at),
                })
              }}
            </span>
            ·
            <span>
              {{ $t('task.comments', comments?.length ?? 0) }}
            </span>
          </div>
        </div>

        <div class="flex gap-1">
          <router-link
            class="btn btn-square btn-ghost btn-sm"
            :to="{ name: 'task-edit', params: { id: task.id } }"
          >
            <PencilIcon class="w-5 h-5" />
          </router-link>
          <button
            class="btn btn-square btn-ghost btn-sm text-error"
            @click="dialog?.showModal()"
          >
            <TrashIcon class="w-5 h-5" />
          </button>
        </div>
      </div>

      <div class="divider mt-1 mb-3 col-span-full"></div>

      <div class="col-span-12 lg:col-span-9">
        <div class="flex flex-col gap-4">
          <div class="flex gap-2">
            <div>
              <UserAvatar :user="task.created_by" size="md" />
            </div>

            <div class="rounded-lg border border-base-content/10 w-full">
              <div
                class="border-b border-base-content/10 px-4 py-2 text-sm label-text"
              >
                <span class="font-bold mr-1">
                  {{ task.created_by.first_name }}
                  {{ task.created_by.last_name }}
                </span>
                <span>
                  {{
                    $t('task.commented_at', {
                      distance: formatDistance(task.created_at),
                    })
                  }}
                </span>
              </div>

              <div
                class="bg-base-300/30 px-4 pt-2 pb-2.5 rounded-b-lg text-base-content"
              >
                <div
                  v-if="task.description"
                  class="prose max-w-none text-base-content"
                  v-html="description"
                ></div>
                <div v-else class="prose max-w-none italic text-base-content">
                  {{ $t('task.no_description') }}
                </div>

                <div v-if="task.children.length > 0">
                  <div class="divider my-1"></div>

                  <h2 class="text-lg">{{ $t('task.subTasks') }}</h2>

                  <ul class="list-disc mb-1">
                    <li v-for="child in task.children" class="list-item ml-4">
                      <router-link
                        :to="{
                          name: 'task-detail',
                          params: { id: child.id },
                        }"
                        class="link"
                      >
                        <span>{{ child.name }} (#{{ child.id }})</span>
                      </router-link>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div v-for="comment in comments">
            <Comment
              :comment="comment"
              @deleteComment="deleteComment"
              @editComment="editComment"
            />
          </div>
        </div>

        <div v-if="auth.user" class="mt-3">
          <div class="flex gap-2">
            <div>
              <UserAvatar :user="auth.user" size="md" />
            </div>
            <div class="flex-1">
              <h1 class="text-base-content mb-2 mt-1">
                {{ $t('task.comment.add') }}
              </h1>
              <textarea
                class="textarea textarea-bordered w-full"
                :placeholder="$t('task.comment.placeholder')"
                v-model="comment"
                v-on:keydown.ctrl.enter="addComment"
              ></textarea>
            </div>
          </div>
          <div class="mt-3 flex gap-2 justify-end">
            <button class="btn">{{ $t('task.close') }} (TODO)</button>
            <button
              class="btn btn-success"
              :disabled="addCommentLoading || comment === ''"
              @click="addComment"
            >
              <span
                v-if="addCommentLoading"
                class="loading loading-spinner"
              ></span>
              {{ $t('task.comment.add') }}
            </button>
          </div>
        </div>
      </div>

      <div class="col-span-12 lg:col-span-3 text-base-content">
        <div>
          <span class="text-sm label-text">{{ $t('task.severity') }}</span>
          <div>{{ task.severity }}</div>
        </div>

        <div class="divider my-0"></div>

        <div>
          <span class="text-sm label-text">{{ $t('task.deadline') }}</span>
          <div>
            {{
              task.deadline
                ? $d(new Date(task.deadline), 'short')
                : $t('task.no_deadline')
            }}
          </div>
        </div>

        <div class="divider my-0"></div>

        <div>
          <div class="text-sm mb-1 label-text">
            {{ $t('task.tags') }}
          </div>

          <div v-if="task.tags.length === 0">
            {{ $t('task.no_tags') }}
          </div>

          <div v-else class="flex flex-wrap gap-1 py-1">
            <TagLabel
              v-for="tag in task.tags"
              :key="tag.id"
              :name="tag.name"
              :color="tag.color"
            />
          </div>
        </div>

        <div class="divider my-0"></div>

        <div>
          <span class="text-sm mb-1 label-text">
            {{ $t('task.assignees') }}
          </span>

          <div v-if="task.users.length === 0">
            {{ $t('task.no_assignees') }}
          </div>

          <div v-else class="flex flex-col gap-y-1 pt-1">
            <div
              v-for="person in task.users"
              :key="person.id"
              class="flex gap-x-2 items-center"
            >
              <UserAvatar :user="person" />
              <span>{{ person.first_name }} {{ person.last_name }}</span>
            </div>
          </div>
        </div>

        <div class="divider my-0"></div>

        <div>
          <span class="text-sm mb-1 label-text">
            {{ $t('task.parent') }}
          </span>

          <div>
            <router-link
              v-if="task.parent"
              :to="{ name: 'task-detail', params: { id: task.parent.id } }"
              class="link"
            >
              {{ task.parent?.name }}
            </router-link>
            <span v-else>{{ $t('task.no_parent') }}</span>
          </div>
        </div>
      </div>
    </div>

    <dialog class="modal" ref="dialog">
      <div class="modal-box flex flex-col">
        <h3 class="font-bold text-lg flex items-center justify-between mb-4">
          {{ $t('task.delete_confirmation.title') }}

          <button
            class="btn btn-ghost btn-circle btn-sm"
            type="button"
            @click="dialog?.close()"
          >
            <XMarkIcon class="w-5 h-5" />
          </button>
        </h3>

        <p>
          {{ $t('task.delete_confirmation.content') }}
        </p>

        <div class="flex justify-end mt-4 gap-2">
          <form method="dialog">
            <button class="btn w-16" type="submit" :disabled="loading">
              {{ $t('no') }}
            </button>
          </form>
          <button class="btn btn-error w-16" type="button" @click="deleteTask">
            <span v-if="loading" class="loading loading-spinner"></span>
            <span v-else>{{ $t('yes') }}</span>
          </button>
        </div>
      </div>
      <form method="dialog" class="modal-backdrop">
        <button>close</button>
      </form>
    </dialog>
  </div>
</template>

<script setup lang="ts">
import Comment from '@/components/Comment.vue'
import TagLabel from '@/components/ui/Tag.vue'
import UserAvatar from '@/components/ui/UserAvatar.vue'
import { useFormatDistance } from '@/composables/useFormatDistance'
import { useAuthStore } from '@/stores/auth'
import { useTeamStore } from '@/stores/team'
import { Task } from '@/types'
import { PencilIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/solid'
import { computed, onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const router = useRouter()
const auth = useAuthStore()
const team = useTeamStore()
const route = useRoute()
const formatDistance = useFormatDistance()
const initialLoading = ref(true)
const task = ref<Task | null>(null)
const dialog = ref<HTMLDialogElement | null>(null)
const loading = ref(false)
const comments = ref<any[]>([])
const comment = ref<string>('')
const addCommentLoading = ref(false)

const description = computed(() => {
  const raw = task.value?.description

  if (!raw) {
    return ''
  }

  return raw
    .replace(/\n/g, '<br>')
    .replace(
      /(https?:\/\/[^\s]+)/g,
      '<a href="$1" target="_blank" class="link">$1</a>',
    )
})

function loadTask() {
  task.value = null
  return auth.client
    .get(`tasks/${team.current?.id}/${route.params.id}`)
    .json()
    .then((res: any) => {
      task.value = res.data as Task
    })
}

function loadComments() {
  return auth.client
    .get(`task/${route.params.id}/comments`)
    .json()
    .then((res: any) => {
      comments.value = res.data
    })
}

function deleteTask() {
  if (loading.value) {
    return
  }

  loading.value = true
  auth.client
    .delete(`tasks/${team.current?.id}/${route.params.id}`)
    .res()
    .then(() => {
      dialog.value?.close()
      router.push({ name: 'home' })
    })
    .finally(() => {
      loading.value = false
    })
}

function addComment() {
  addCommentLoading.value = true

  auth.client
    .post({ comment: comment.value }, `task/${route.params.id}/comments`)
    .json()
    .then((res: any) => {
      res.data.user = auth.user
      comments.value.push(res.data)
      comment.value = ''
    })
    .finally(() => {
      addCommentLoading.value = false
    })
}

function deleteComment(id: number) {
  auth.client
    .delete(`task/${route.params.id}/comments/${id}`)
    .res()
    .then(() => {
      comments.value = comments.value.filter((c) => c.id !== id)
    })
}

function editComment(args: any) {
  const { id, text } = args
  console.log(id, text)
  auth.client
    .put({ comment: text }, `task/${route.params.id}/comments/${id}`)
    .res()
    .then(() => {
      comments.value = comments.value.map((c) => {
        if (c.id === id) {
          c.comment = text
        }

        return c
      })
    })
}

watch(
  () => route.params.id,
  () => {
    loadTask()
    loadComments()
  },
)

onMounted(() => {
  loadTask()
    .then(loadComments)
    .finally(() => {
      initialLoading.value = false
    })
})
</script>
