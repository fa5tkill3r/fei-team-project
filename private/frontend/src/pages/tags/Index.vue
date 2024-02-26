<template>
  <div>
    <h1 class="text-2xl font-bold">{{ $t('tags.title') }}</h1>

    <div v-if="initialLoading" class="mt-4">
      <div class="loading loading-spinner"></div>
    </div>

    <div v-else>
      <div v-if="tags.length === 0" class="mt-4">
        <p>{{ $t('tags.no_tags') }}</p>
      </div>

      <div v-else class="mt-4">
        <div class="mb-3 flex justify-between">
          <input
            type="text"
            class="input input-bordered"
            :placeholder="$t('tags.search')"
            v-model="query"
          />

          <div>
            <button class="btn btn-primary">
              {{ $t('add') }}
            </button>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="table rounded overflow-hidden">
            <thead>
              <tr>
                <th class="text-sm font-normal bg-base-200" colspan="2">
                  {{ $t('tags.count', { count: tags.length }) }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="tag in tags" :key="tag.id">
                <td>
                  <div class="ml-2 badge" :style="getStylesForTag(tag)">
                    {{ tag.name }}
                  </div>
                </td>
                <td class="flex justify-end gap-1">
                  <button class="btn btn-sm btn-outline btn-warning">
                    {{ $t('edit') }}
                  </button>
                  <button class="btn btn-sm btn-outline btn-error">
                    {{ $t('delete') }}
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { useAuthStore } from '@/stores/auth'
import { Tag } from '@/types'
import { onMounted, ref } from 'vue'
import { getStylesForTag } from '@/lib/utils'

const auth = useAuthStore()
const initialLoading = ref(true)
const tags = ref<Tag[]>([])
const query = ref('')

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

// function addTag() {
//   if (!newTag.value) {
//     return
//   }

//   const color = '#' + Math.floor(Math.random() * 16777215).toString(16)

//   auth.client
//     .post({ name: newTag.value, color }, 'tags')
//     .json()
//     .then((response: any) => {
//       tags.value.push(response.data)
//       newTag.value = ''
//     })
// }

onMounted(() => {
  loadTags()
})
</script>
