<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { ChatBubbleBottomCenterTextIcon } from '@heroicons/vue/24/outline'
import {
  Squares2X2Icon,
  ListBulletIcon,
  EllipsisHorizontalIcon,
  PlusIcon,
} from '@heroicons/vue/24/solid'
import { ref } from 'vue'
import { useTeamStore } from '@/stores/team.ts'
import { watch } from 'vue'
import { onMounted } from 'vue'
import { Task } from '@/types'
import { getStylesForTag } from '@/lib/utils'
import UserAvatar from '@/components/UserAvatar.vue'

const auth = useAuthStore()
const teamStore = useTeamStore()
const tasks = ref<Task[]>([])

function loadTasks(team: any = null) {
  auth.client
    .query({
      team: team?.id,
    })
    .get('tasks')
    .json()
    .then((res: any) => {
      tasks.value = res.data
    })
    .catch((err) => {
      console.log(err)
    })
}

watch(
  () => teamStore.current,
  (team) => {
    loadTasks(team)
  },
)

onMounted(() => {
  loadTasks()
})
</script>

<template>
  <div>
    <div>
      <h1 class="font-bold text-2xl">{{ $t('main.tasks')}}</h1>

      <div class="join mt-4">
        <button class="btn join-item">
          <Squares2X2Icon class="w-5 h-5" />
          {{ $t('main.board')}}
        </button>
        <button class="btn join-item">
          <ListBulletIcon class="w-5 h-5" />
          {{ $t('main.list')}}
        </button>
      </div>
    </div>

    <div class="grid grid-cols-4 gap-6 mt-6">
      <div v-for="_ in 1" class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
          <h2>{{ $t('main.tasks')}}</h2>

          <button class="btn btn-square btn-ghost">
            <EllipsisHorizontalIcon class="w-5 h-5" />
          </button>
        </div>

        <router-link
          v-for="task in tasks"
          class="bg-base-300 p-6 rounded-lg"
          :to="{ name: 'task-detail', params: { id: task.id } }"
        >
          <div class="mb-2">
            <p class="font-semibold text-xl">{{ task.name }}</p>
          </div>

          <div v-if="task.tags" class="flex gap-1 mb-4 flex-wrap">
            <div
              v-for="tag in task.tags"
              class="badge"
              :style="getStylesForTag(tag)"
            >
              {{ tag.name }}
            </div>
          </div>

          <div class="flex justify-between items-center">
            <div class="avatar-group -space-x-6 rtl:space-x-reverse">
              <UserAvatar
                v-for="person in task.users.slice(0, 3)"
                class="border-base-300"
                :key="person.id"
                :user="person"
                size="md"
              />

              <div
                v-if="task.users.length > 3"
                class="avatar placeholder border-base-300"
              >
                <div class="w-10 bg-neutral">
                  <span> +{{ task.users.length - 3 }} </span>
                </div>
              </div>
            </div>

            <div>
              <span class="flex items-center gap-1">
                <ChatBubbleBottomCenterTextIcon class="w-5 h-5" />
                23
              </span>
            </div>
          </div>
        </router-link>
      </div>
    </div>

    <div class="fixed bottom-6 right-6">
      <router-link
        class="btn btn-circle btn-lg btn-primary"
        :to="{ name: 'task-create' }"
      >
        <PlusIcon class="w-6 h-6" />
      </router-link>
    </div>
  </div>
</template>
