<template>
  <div>
    <button
      type="button"
      class="flex w-full justify-between text-sm hover:text-primary py-2 label-text"
      @click="openDialog"
    >
      <span>{{ $t('task.parent') }}</span>
      <Cog6ToothIcon class="w-5 h-5" />
    </button>

    <div class="pb-2 text-base-content">
      <p v-if="!model">
        {{ $t('task.no_parent') }}
        <!-- <span class="link" @click="">{{ $t('task.assign-yourself') }}</span> -->
      </p>

      <div v-else-if="taskMap[model]" class="flex flex-col gap-1">
        <router-link
          :to="{ name: 'task-detail', params: { id: model } }"
          target="_blank"
        >
          {{ taskMap[model].name }}
        </router-link>
        <!-- <div
          v-for="personId in model"
          :key="personId"
          class="flex items-center gap-2"
        >
          <UserAvatar :user="personMap[personId]" />
          <span>
            {{ personMap[personId].first_name }}
            {{ personMap[personId].last_name }}
          </span>
        </div> -->
      </div>
    </div>

    <!-- TODO: add fixed height -->
    <!-- TODO: add clear query -->
    <!-- TODO: add clear selection -->
    <dialog class="modal" ref="dialog">
      <div class="modal-box flex flex-col min-h-96 max-h-full">
        <h3 class="font-bold text-lg flex items-center justify-between mb-4">
          {{ $t('task.select_parent') }}

          <button
            class="btn btn-ghost btn-circle btn-sm"
            type="button"
            @click="dialog?.close()"
          >
            <XMarkIcon class="w-5 h-5" />
          </button>
        </h3>

        <input
          type="text"
          v-model="query"
          class="input input-bordered w-full mb-3"
          placeholder="Search"
        />

        <div class="flex flex-col flex-1 gap-1.5 overflow-y-auto">
          <label
            v-for="task in filteredTasks"
            :key="task.id"
            class="flex items-center p-2 rounded-btn cursor-pointer hover:bg-primary hover:text-primary-content transition-colors duration-200 ease-out"
          >
            <input
              type="radio"
              name="parent"
              v-model="model"
              :value="task.id"
              class="hidden"
            />

            <span class="w-6">
              <CheckIcon v-if="model === task.id" class="w-5 h-5" />
            </span>

            <p class="">
              <span>{{ task.name }}</span>
            </p>
          </label>

          <label
            class="flex items-center p-2 rounded-btn cursor-pointer hover:bg-primary hover:text-primary-content transition-colors duration-200 ease-out"
          >
            <input
              type="radio"
              name="parent"
              v-model="model"
              :value="undefined"
              class="hidden"
            />
            <span class="w-6">
              <CheckIcon v-if="model === undefined" class="w-5 h-5" />
            </span>
            <p class="">{{ $t('task.no_parent') }}</p>
          </label>

          <p v-if="filteredTasks.length === 0">
            {{ $t('task.no_results') }}
          </p>
        </div>
      </div>
      <form method="dialog" class="modal-backdrop">
        <button>close</button>
      </form>
    </dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Cog6ToothIcon, CheckIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { useTeamStore } from '@/stores/team'
import { useAuthStore } from '@/stores/auth'
import { Task } from '@/types'
import { onMounted } from 'vue'

const team = useTeamStore()
const auth = useAuthStore()
const model = defineModel<number | undefined>()
const props = defineProps<{ taskId?: number }>()
const tasks = ref<Task[]>([])
const dialog = ref<HTMLDialogElement | null>(null)
const query = ref('')
const taskMap = computed(() =>
  Object.fromEntries(tasks.value.map((p) => [p.id, p])),
)

const filteredTasks = computed(() => {
  const search = query.value.toLowerCase()

  return search === ''
    ? tasks.value
    : tasks.value.filter((task) => {
        return task.name.includes(search)
      })
})

function loadTasks() {
  if (!team.current) return

  auth.client
    .query({
      team: team.current?.id,
    })
    .get('tasks')
    .json()
    .then((res: any) => {
      tasks.value = res.data.filter((task: Task) => task.id !== props.taskId)
    })
    .catch((err) => {
      console.log(err)
    })
}

function openDialog() {
  dialog.value?.showModal()
}

function save() {
  dialog.value?.close()
}

watch(model, () => {
  if (dialog.value?.open) {
    save()
  }
})

onMounted(() => {
  loadTasks()
})
</script>
