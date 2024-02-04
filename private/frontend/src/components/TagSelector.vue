<template>
  <div>
    <button
      type="button"
      class="flex w-full justify-between text-sm hover:text-primary py-2"
      @click="openDialog"
    >
      <span>{{ $t('task.tags') }}</span>
      <Cog6ToothIcon class="w-5 h-5" />
    </button>

    <div class="pb-2 text-sm text-neutral-200">
      <p v-if="!model || model.length === 0">
        {{ $t('task.no_tags') }}
      </p>

      <div v-else>
        <div
          v-for="tagId in model"
          :key="tagId"
          class="flex items-center gap-2"
        >
          <span class="badge">{{ tagMap[tagId].name }}</span>
        </div>
      </div>
    </div>

    <dialog class="modal" ref="dialog">
      <div class="modal-box flex flex-col min-h-96 max-h-full">
        <h3 class="font-bold text-lg flex items-center justify-between mb-4">
          {{ $t('task.select_tags') }}

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

        <div class="flex flex-col flex-1 gap-2.5 overflow-y-auto">
          <label
            v-for="tag in filteredTags"
            :key="tag.id"
            class="flex items-center p-2 rounded-btn cursor-pointer hover:bg-primary hover:text-primary-content transition-colors"
          >
            <input
              type="checkbox"
              v-model="tempModel"
              :value="tag.id"
              class="hidden"
            />

            <span class="w-6">
              <CheckIcon v-if="tempModel.includes(tag.id)" class="w-5 h-5" />
            </span>
            <div class="avatar">
              <div class="w-8 rounded-full">
                <img
                  src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg"
                />
              </div>
            </div>

            <p class="ml-2">
              <!-- <span class="mr-1 font-medium">
                {{ person.email }}
              </span> -->
              <span>{{ tag.name }}</span>
            </p>
          </label>

          <p v-if="filteredTags.length === 0">
            {{ $t('task.no_results') }}
          </p>
        </div>

        <div class="flex justify-end mt-4">
          <button class="btn btn-primary" @click="save" type="button">
            {{ $t('task.save') }}
          </button>
        </div>
      </div>
      <form method="dialog" class="modal-backdrop">
        <button>close</button>
      </form>
    </dialog>
  </div>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import { Cog6ToothIcon, CheckIcon, XMarkIcon } from '@heroicons/vue/24/outline'

interface Tag {
  id: number
  name: string
}

const model = defineModel<number[]>()
const query = ref('')
const dialog = ref<HTMLDialogElement | null>(null)
const tempModel = ref<number[]>([])
const tags = ref<Tag[]>([])
const tagMap = computed(() =>
  Object.fromEntries(tags.value.map((tag) => [tag.id, tag])),
)
const filteredTags = computed(() => {
  if (!query.value) {
    return tags.value
  }

  return tags.value.filter((tag) =>
    tag.name.toLowerCase().includes(query.value.toLowerCase()),
  )
})

function openDialog() {
  dialog.value?.showModal()
}

function save() {}
</script>
