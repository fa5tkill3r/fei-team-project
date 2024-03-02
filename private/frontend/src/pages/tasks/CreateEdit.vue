<template>
  <form
    @submit.prevent="saveTask"
    class="grid grid-cols-12 gap-x-4 gap-y-4 max-w-7xl mx-auto"
    v-if="!initialLoading"
  >
    <PageTitle :text="edit ? 'task.editing' : 'task.new'" :task="task.name" />

    <div class="col-span-12 xl:col-span-9">
      <label class="form-control w-full">
        <div class="label">
          <span class="label-text">{{ $t('task.title') }}</span>
        </div>
        <input
          type="text"
          v-model="task.name"
          :placeholder="$t('task.title_placeholder')"
          class="input input-bordered w-full"
        />
      </label>

      <label class="form-control">
        <div class="label">
          <span class="label-text">{{ $t('task.description') }}</span>
        </div>
        <textarea
          class="textarea textarea-bordered h-24 text-base"
          :placeholder="$t('task.description_placeholder')"
          v-model="task.description"
        ></textarea>
      </label>

      <div class="text-right hidden lg:block">
        <button type="submit" class="btn btn-primary mt-4" :disabled="loading">
          {{ $t('task.save') }}
        </button>
      </div>
    </div>

    <div class="col-span-12 xl:col-span-3">
      <label class="form-control">
        <div class="label">
          <span class="label-text">{{ $t('task.severity') }}</span>
        </div>
        <!-- TODO: add better styles and figure out what values are actually supposed to be here -->
        <select
          class="select select-bordered select-sm w-full max-w-xs"
          v-model="task.severity"
        >
          <option>low</option>
          <option>medium</option>
          <option>high</option>
        </select>
      </label>

      <div class="divider my-0"></div>

      <div class="form-control">
        <div class="label">
          <span class="label-text">{{ $t('task.deadline') }}</span>
        </div>

        <!-- TODO: add better styles -->
        <DatePicker
          v-model="task.deadline"
          :enable-time-picker="false"
          auto-apply
        />
      </div>

      <div class="divider my-0"></div>

      <TagSelector v-model="task.tags" />

      <div class="divider my-0"></div>

      <UserSelector v-model="task.users" />

      <div class="divider my-0"></div>

      <TaskSelector v-model="task.parent" :task-id="id" />
    </div>

    <div class="col-span-12 xl:col-span-9 text-right lg:hidden">
      <button type="submit" class="btn btn-primary mt-4" :disabled="loading">
        {{ $t('task.save') }}
      </button>
    </div>
  </form>
</template>

<script setup lang="ts">
import TagSelector from '@/components/TagSelector.vue'
import TaskSelector from '@/components/TaskSelector.vue'
import UserSelector from '@/components/UserSelector.vue'
import { useAuthStore } from '@/stores/auth'
import { useTeamStore } from '@/stores/team'
import { TaskRequest } from '@/types'
import DatePicker from '@vuepic/vue-datepicker'
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

import '@vuepic/vue-datepicker/dist/main.css'

const { edit, id } = defineProps<{ edit?: boolean; id?: number }>()
const router = useRouter()
const auth = useAuthStore()
const team = useTeamStore()
const loading = ref(false)
const initialLoading = ref(true)
const task = ref<TaskRequest>({
  name: '',
  description: '',
  users: [],
  tags: [],
  severity: 'low',
  parent: undefined,
})

function loadTask() {
  initialLoading.value = true
  auth.client
    .get(`tasks/${team.current?.id}/${id}`)
    .json()
    .then((res: any) => {
      task.value = {
        name: res.data.name,
        description: res.data.description,
        severity: res.data.severity,
        deadline: res.data.deadline,
        users: res.data.users.map((u: any) => u.id),
        tags: res.data.tags.map((t: any) => t.id),
        parent: res.data.parent?.id,
      }
    })
    .finally(() => {
      initialLoading.value = false
    })
}

function saveTask() {
  loading.value = true

  const request = edit
    ? auth.client.put(task.value, `tasks/${team.current?.id}/${id}`)
    : auth.client.post(task.value, `tasks/${team.current?.id}`)

  request
    .json()
    .then((res: any) => {
      router.push({ name: 'task-detail', params: { id: res.data.id } })
    })
    .finally(() => {
      loading.value = false
    })
}

onMounted(() => {
  if (edit && id) {
    loadTask()
  } else {
    initialLoading.value = false
  }
})
</script>
