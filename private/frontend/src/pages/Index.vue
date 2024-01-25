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

const auth = useAuthStore()
const teamStore = useTeamStore()
const tasks = ref<any[]>([])

function getTasks(team: any = null) {
  console.log(team)
  auth.client.query({
    team: team?.id,
  }).get('tasks').then((res : any) => {
    tasks.value = res.data
  }).catch((err) => {
    console.log(err)
  })
}

const onTeamChange = (team: any) => {
  getTasks(team)
}

teamStore.onTeamChange(onTeamChange)

getTasks()
</script>

<template>
  <div>
    <div>
      <h1 class="font-bold text-2xl">Tasks</h1>

      <div class="join mt-4">
        <button class="btn join-item">
          <Squares2X2Icon class="w-5 h-5" />
          Board view
        </button>
        <button class="btn join-item">
          <ListBulletIcon class="w-5 h-5" />
          List view
        </button>
      </div>
    </div>

    <div class="grid grid-cols-4 gap-6 mt-6">
      <div v-for="_ in 1" class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
          <h2>Tasks</h2>

          <button class="btn btn-square btn-ghost">
            <EllipsisHorizontalIcon class="w-5 h-5" />
          </button>
        </div>

        <div v-for="task in tasks" class="bg-neutral p-6 rounded-lg">
          <div class="mb-2">
            <p class="font-semibold text-xl">{{ task.title }}</p>
          </div>

          <div class="flex gap-2 mb-4">
            <span class="badge">Type</span>
          </div>

          <div class="flex justify-between items-center">
            <div class="avatar-group -space-x-6 rtl:space-x-reverse">
              <div class="avatar border-neutral">
                <div class="w-10">
                  <img
                    src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg"
                  />
                </div>
              </div>
              <div class="avatar border-neutral">
                <div class="w-10">
                  <img
                    src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg"
                  />
                </div>
              </div>
              <div class="avatar border-neutral">
                <div class="w-10">
                  <img
                    src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg"
                  />
                </div>
              </div>
              <div class="avatar placeholder border-neutral">
                <div class="w-10 bg-base-100">
                  <span>+5</span>
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
        </div>
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
