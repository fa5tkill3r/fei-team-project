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
            <span>{{ $t('task.created_at', task.created_at) }}</span>
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
        <div
          class="rounded px-4 py-3 border border-neutral prose max-w-none"
          v-html="description"
        ></div>
      </div>

      <div class="col-span-12 lg:col-span-3">
        <div>
          <div class="flex justify-between text-sm">
            <span>{{ $t('task.severity') }}</span>
            <span>x</span>
          </div>

          <div>{{ task.severity }}</div>
        </div>

        <div class="divider my-0"></div>

        <div>
          <div class="flex justify-between text-sm">
            <span>{{ $t('task.deadline') }}</span>
            <span>x</span>
          </div>

          <div>{{ task.deadline || 'none' }}</div>
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

const auth = useAuthStore()
const team = useTeamStore()
const route = useRoute()
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
    .get(`tasks/${team.team?.id}/${route.params.id}`)
    .then((res: any) => {
      task.value = res.data as Task
    })
})
</script>
