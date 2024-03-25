<script setup lang="ts">
import TaskStatus from '@/components/TaskStatus.vue'
import Dropdown from '@/components/ui/Dropdown.vue'
import TagLabel from '@/components/ui/Tag.vue'
import UserAvatar from '@/components/ui/UserAvatar.vue'
import DropdownButton from '@/components/ui/dropdown/DropdownButton.vue'
import DropdownLink from '@/components/ui/dropdown/DropdownLink.vue'
import { debounce } from '@/lib/utils'
import { useAuthStore } from '@/stores/auth'
import { useTeamStore } from '@/stores/team.ts'
import { Task } from '@/types'
import { ChatBubbleBottomCenterTextIcon } from '@heroicons/vue/24/outline'
import {
  ChevronDownIcon,
  ListBulletIcon,
  PlusIcon,
  Squares2X2Icon,
  XMarkIcon,
} from '@heroicons/vue/24/solid'
import { computed, onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const router = useRouter()
const route = useRoute()
const auth = useAuthStore()
const teamStore = useTeamStore()
const loading = ref(true)
const filterHelpOpen = ref(false)
const tasks = ref<Task[]>([])
const viewType = ref('board')
const query = ref((route.query.q as string | null) ?? '')
const filters = computed(() => [
  { label: 'open', value: 'is:open' },
  { label: 'closed', value: 'is:closed' },
  { label: 'your', value: 'assignee:@me' },
])

function loadTasks(team: any = null) {
  loading.value = true
  auth.client
    .query({
      team: team?.id ?? teamStore.current?.id,
      query: query.value,
    })
    .get('tasks')
    .json()
    .then((res: any) => {
      tasks.value = res.data
    })
    .catch((err) => {
      console.log(err)
    })
    .finally(() => {
      loading.value = false
    })
}

function loadTasksAndUpdateQuery() {
  router.replace({ query: { q: query.value || undefined } })
  loadTasks()
}

const loadTasksDebounced = debounce(loadTasksAndUpdateQuery, 400)

watch(
  () => teamStore.current,
  (team) => {
    loadTasks(team)
  },
)

watch(
  () => route.query,
  () => {
    query.value = (route.query.q as string | null) ?? ''
    loadTasks()
  },
)

onMounted(() => {
  loadTasks()
})
</script>

<template>
  <div>
    <PageTitle text="nav.tasks" />

    <div>
      <h1 class="font-bold text-2xl">{{ $t('main.tasks') }}</h1>

      <div class="join mt-4">
        <button class="btn join-item" @click="viewType = 'board'">
          <Squares2X2Icon class="w-5 h-5" />
          {{ $t('main.board') }}
        </button>
        <button class="btn join-item" @click="viewType = 'list'">
          <ListBulletIcon class="w-5 h-5" />
          {{ $t('main.list') }}
        </button>
      </div>

      <div class="mt-4 flex gap-2 max-w-6xl">
        <div class="join w-full relative">
          <Dropdown class="left-0 menu-sm">
            <template #button>
              <button class="btn join-item w-28">
                {{ $t('task.filters') }}
                <ChevronDownIcon class="w-4 h-4" />
              </button>
            </template>

            <DropdownLink
              v-for="filter in filters"
              :key="filter.label"
              :to="{ name: 'home', query: { q: filter.value }, replace: true }"
            >
              {{ $t(`task.filter.${filter.label}`) }}
            </DropdownLink>

            <div class="divider my-0"></div>

            <DropdownButton @click="filterHelpOpen = true">
              {{ $t('task.filter_help') }}
            </DropdownButton>
          </Dropdown>

          <input
            v-model="query"
            type="text"
            class="input input-bordered w-full join-item"
            @input="loadTasksDebounced"
          />

          <div
            v-if="filterHelpOpen"
            class="absolute top-12 mt-2 bg-base-200 rounded-lg p-4 w-full z-10"
          >
            <p v-html="$t('task.filter_docs')"></p>

            <button
              class="btn btn-sm absolute top-0 right-0 mt-2 mr-2"
              @click="filterHelpOpen = false"
            >
              <XMarkIcon class="w-4 h-4" />
            </button>
          </div>
        </div>

        <router-link :to="{ name: 'tags' }" class="btn">
          {{ $t('task.manage_tags') }}
        </router-link>
      </div>
    </div>

    <div v-if="loading" class="flex justify-center w-full mt-4">
      <div class="loading"></div>
    </div>

    <div v-else-if="tasks.length" class="flex flex-col mt-4">
      <masonry v-if="viewType === 'board'" :cols="4" :gutter="15">
        <router-link
          v-for="task in tasks"
          class="block bg-base-300 p-6 rounded-lg mb-4"
          :to="{ name: 'task-detail', params: { id: task.id } }"
        >
          <div class="mb-2 flex justify-between">
            <p class="font-semibold text-xl">{{ task.name }}</p>
          </div>

          <div v-if="task.tags" class="flex gap-1 mb-4 flex-wrap">
            <TaskStatus :task="task" />
            <TagLabel
              v-for="tag in task.tags"
              :key="tag.id"
              :name="tag.name"
              :color="tag.color"
            />
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
                {{ task.comments_count }}
              </span>
            </div>
          </div>
        </router-link>
      </masonry>

      <div v-if="viewType === 'list'">
        <div class="overflow-x-auto">
          <table class="table w-full">
            <thead>
              <tr>
                <th>{{ $t('task.title') }}</th>
                <th>{{ $t('task.header_status') }}</th>
                <th>{{ $t('task.assignees') }}</th>
                <th>{{ $t('task.header_comments') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="task in tasks" :key="task.id" class="h-14">
                <td>
                  <router-link
                    :to="{ name: 'task-detail', params: { id: task.id } }"
                  >
                    {{ task.name }}
                  </router-link>
                </td>
                <td>
                  <TaskStatus :task="task" />
                </td>
                <td class="py-2">
                  <div class="avatar-group -space-x-6 rtl:space-x-reverse">
                    <UserAvatar
                      v-for="person in task.users.slice(0, 3)"
                      class="border-base-100"
                      :key="person.id"
                      :user="person"
                      size="sm"
                    />

                    <div
                      v-if="task.users.length > 3"
                      class="avatar placeholder border-base-100"
                    >
                      <div class="w-8 text-sm bg-base-200">
                        <span> +{{ task.users.length - 3 }} </span>
                      </div>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="flex items-center gap-1">
                    <ChatBubbleBottomCenterTextIcon class="w-5 h-5" />
                    {{ task.comments_count }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div v-else class="flex justify-center w-full mt-4">
      <p>{{ $t('task.no_tasks') }}</p>
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
