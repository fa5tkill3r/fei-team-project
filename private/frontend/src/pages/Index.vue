<script setup lang="ts">
import { ChatBubbleBottomCenterTextIcon } from '@heroicons/vue/24/outline'
import { Squares2X2Icon, ListBulletIcon, EllipsisHorizontalIcon } from '@heroicons/vue/24/solid'
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
      <div v-for="_ in 4" class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
          <h2>Tasks</h2>

          <button class="btn btn-square btn-ghost">
            <EllipsisHorizontalIcon class="w-5 h-5" />
          </button>
        </div>

        <div v-for="_ in 5" class="bg-neutral p-6 rounded-lg">
          <div class="mb-2">
            <p class="font-semibold text-xl">Title</p>
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
  </div>
</template>
