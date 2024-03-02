<template>
  <div>
    <h1 class="text-2xl font-bold">{{ $t('tags.title') }}</h1>

    <div v-if="initialLoading" class="mt-4">
      <div class="loading loading-spinner"></div>
    </div>

    <div v-else>
      <div class="mt-4">
        <div class="mb-3 flex justify-between">
          <input
            type="text"
            class="input input-bordered"
            :placeholder="$t('tags.search')"
            v-model="query"
          />

          <div>
            <button class="btn btn-primary" @click="dialogOpen = true">
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
                  <TagLabel :name="tag.name" :color="tag.color" />
                </td>
                <td class="flex justify-end gap-1">
                  <button
                    class="btn btn-sm btn-outline btn-warning"
                    @click="edit(tag)"
                  >
                    {{ $t('edit') }}
                  </button>
                  <!-- TODO: implement delete -->
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

    <TagDialog v-model="dialogOpen" :edit="editing" @update="updateTag" />
  </div>
</template>

<script lang="ts" setup>
import TagDialog from '@/components/TagDialog.vue'
import TagLabel from '@/components/ui/Tag.vue'
import { useAuthStore } from '@/stores/auth'
import { Tag } from '@/types'
import { onMounted, ref, watch } from 'vue'

const auth = useAuthStore()
const initialLoading = ref(true)
const tags = ref<Tag[]>([])
const query = ref('')

const dialogOpen = ref(false)
const editing = ref<Tag | null>(null)

watch(dialogOpen, (value) => {
  if (!value) {
    editing.value = null
  }
})

function edit(tag: Tag) {
  editing.value = tag
}

function updateTag(tag: Tag) {
  const index = tags.value.findIndex((t) => t.id === tag.id)

  if (index !== -1) {
    tags.value[index] = tag
  } else {
    tags.value.push(tag)
  }
}

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

onMounted(() => {
  loadTags()
})
</script>
