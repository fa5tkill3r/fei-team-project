<template>
  <div>
    <button
      type="button"
      class="flex w-full justify-between text-sm hover:text-primary py-2 label-text"
      @click="openDialog"
    >
      <span>{{ $t('task.tags') }}</span>
      <Cog6ToothIcon class="w-5 h-5" />
    </button>

    <div class="text-neutral-200">
      <p v-if="!model || model.length === 0 || initialLoading">
        {{ $t('task.no_tags') }}
      </p>

      <div v-else class="flex flex-wrap gap-1 pb-1">
        <div
          v-for="tagId in model"
          :key="tagId"
          class="badge"
          :style="getStylesForTag(tagMap[tagId])"
        >
          {{ tagMap[tagId].name }}
        </div>
      </div>
    </div>

    <!-- TODO: add fixed height -->
    <!-- TODO: add clear query -->
    <!-- TODO: add clear selection -->
    <dialog class="modal" ref="dialog">
      <div class="modal-box flex flex-col h-[70%]">
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

        <div class="flex flex-col flex-1 gap-1 overflow-y-auto">
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

            <div class="ml-2 badge" :style="getStylesForTag(tag)">
              {{ tag.name }}
            </div>
          </label>

          <p v-if="filteredTags.length === 0">
            {{ $t('task.no_results') }}
          </p>
        </div>

        <div class="flex flex-wrap justify-between mt-4 gap-2">
          <div>
            <div class="flex gap-2">
              <input
                v-model="newTag"
                type="text"
                class="input input-bordered w-full"
              />

              <button class="btn btn-secondary" @click="addTag" type="button">
                Add
              </button>
            </div>
          </div>
          <div class="text-right w-full md:w-auto">
            <button class="btn btn-primary" @click="save" type="button">
              {{ $t('task.save') }}
            </button>
          </div>
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
import { Tag } from '@/types'
import { useAuthStore } from '@/stores/auth'
import { onMounted } from 'vue'
import { getStylesForTag } from '@/lib/utils'

const auth = useAuthStore()
const model = defineModel<number[]>()
const tempModel = ref<number[]>([])
const initialLoading = ref(true)
const query = ref('')
const newTag = ref('')
const dialog = ref<HTMLDialogElement | null>(null)
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

function loadTags() {
  auth.client
    .get('tags')
    .json()
    .then((response: any) => {
      tags.value = response.data
    })
    .finally(() => {
      initialLoading.value = false
    })
}

function addTag() {
  if (!newTag.value) {
    return
  }

  const color = '#' + Math.floor(Math.random() * 16777215).toString(16)

  auth.client
    .post({ name: newTag.value, color }, 'tags')
    .json()
    .then((response: any) => {
      tags.value.push(response.data)
      newTag.value = ''
    })
}

function openDialog() {
  tempModel.value = model.value ? [...model.value] : []
  dialog.value?.showModal()
}

function save() {
  model.value = [...tempModel.value]
  dialog.value?.close()
}

onMounted(() => {
  loadTags()
})
</script>
