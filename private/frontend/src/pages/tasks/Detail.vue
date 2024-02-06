<template>
  <div class="max-w-7xl mx-auto">
    <div v-if="task" class="grid grid-cols-12 gap-x-4">
      <div class="col-span-full flex justify-between">
        <div>
          <h1 class="text-2xl font-bold text-neutral-200">
            {{ task.name }}
            <span class="text-base-content">#{{ task.id }}</span>
          </h1>
          <div>
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
              {{
                $t('task.comments', {
                  count: 0,
                })
              }}
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
          <button class="btn btn-square btn-ghost btn-sm text-error">
            <TrashIcon class="w-5 h-5" />
          </button>
        </div>
      </div>

      <div class="divider mt-1 mb-3 col-span-full"></div>

      <div class="col-span-12 lg:col-span-9">
        <div class="flex gap-2">
          <div>
            <UserAvatar :user="task.created_by" size="md" />
          </div>

          <div class="rounded-lg border border-neutral w-full">
            <div class="border-b border-neutral px-4 py-2 text-sm">
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
              class="prose prose-green max-w-none bg-base-200 px-4 pt-2 pb-2.5 rounded-b-lg"
              v-html="description"
            ></div>
          </div>
        </div>

        <div class="flex flex-col gap-4 mt-6">
          <div v-for="_ in 5">
            <div class="flex gap-2">
              <div>
                <div class="avatar placeholder">
                  <div
                    class="w-10 rounded-full bg-neutral text-neutral-content"
                  >
                    <span>TD</span>
                  </div>
                </div>
              </div>

              <div class="rounded-lg border border-neutral w-full">
                <div class="border-b border-neutral px-4 py-2 text-sm">
                  <b>TODO</b> commented 2 days ago
                </div>

                <div
                  class="prose max-w-none bg-base-200 px-4 pt-2 pb-2.5 rounded-b-lg"
                >
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin
                  suscipit libero et nibh tincidunt rutrum. Nulla laoreet eros
                  id nibh cursus feugiat. Suspendisse potenti. Interdum et
                  malesuada fames ac ante ipsum primis in faucibus. Suspendisse
                  quis dui a risus facilisis mollis quis vel orci. Proin
                  faucibus odio eget ligula laoreet, in tempor elit dignissim.
                  Mauris nec porttitor arcu, at sagittis turpis.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-span-12 lg:col-span-3">
        <div>
          <span class="text-sm">{{ $t('task.severity') }}</span>
          <div class="text-neutral-200">{{ task.severity }}</div>
        </div>

        <div class="divider my-0"></div>

        <div>
          <span class="text-sm">{{ $t('task.deadline') }}</span>
          <div class="text-neutral-200">
            {{
              task.deadline
                ? $d(new Date(task.deadline), 'short')
                : $t('task.no_deadline')
            }}
          </div>
        </div>

        <div class="divider my-0"></div>

        <div>
          <div class="text-sm mb-1">
            {{ $t('task.tags') }}
          </div>

          <div v-if="task.tags.length === 0" class="text-neutral-200">
            {{ $t('task.no_tags') }}
          </div>

          <div v-else class="flex flex-wrap gap-1 py-1">
            <div
              v-for="tag in task.tags"
              :key="tag.id"
              class="badge"
              :style="getStylesForTag(tag)"
            >
              {{ tag.name }}
            </div>
          </div>
        </div>

        <div class="divider my-0"></div>

        <div>
          <span class="text-sm mb-1">
            {{ $t('task.assignees') }}
          </span>

          <div v-if="task.users.length === 0" class="text-neutral-200">
            {{ $t('task.no_assignees') }}
          </div>

          <div v-else class="flex flex-col gap-y-1 text-neutral-200 pt-1">
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
          <span class="text-sm mb-1">
            {{ $t('task.parent') }}
          </span>

          <div class="text-neutral-200">TODO</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import { Task } from '@/types'
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useTeamStore } from '@/stores/team'
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/solid'
import { computed } from 'vue'
import { useFormatDistance } from '@/composables/useFormatDistance'
import { getStylesForTag } from '@/lib/utils'
import UserAvatar from '@/components/UserAvatar.vue'

const auth = useAuthStore()
const team = useTeamStore()
const route = useRoute()
const formatDistance = useFormatDistance()
const task = ref<Task | null>(null)

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

onMounted(() => {
  auth.client
    .get(`tasks/${team.current?.id}/${route.params.id}`)
    .then((res: any) => {
      task.value = res.data as Task
    })
})
</script>
