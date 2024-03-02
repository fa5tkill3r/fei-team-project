<template>
  <div>
    <PageTitle text="tags.title" />

    <h1 class="text-2xl font-bold">{{ $t('tags.title') }}</h1>

    <div v-if="initialLoading" class="mt-4">
      <div class="loading loading-spinner"></div>
    </div>

    <div v-else>
      <div class="mt-4">
        <div class="mb-3">
          <input
            type="text"
            class="input input-bordered"
            :placeholder="$t('tags.search')"
            v-model="query"
          />
        </div>

        <div class="overflow-x-auto">
          <table class="table rounded overflow-hidden">
            <thead>
              <tr>
                <th class="text-sm font-normal bg-base-200" colspan="2">
                  {{ $t('tags.count', { count: filteredTags.length }) }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="tag in filteredTags" :key="tag.id">
                <td>
                  <TagLabel :name="tag.name" :color="tag.color" />
                </td>
                <td class="flex justify-end gap-1">
                  <button
                    class="btn btn-sm btn-ghost btn-square"
                    @click="edit(tag)"
                  >
                    <PencilIcon class="h-5 w-5" />
                  </button>
                  <button
                    class="btn btn-sm btn-ghost btn-square text-error"
                    @click="remove(tag)"
                  >
                    <TrashIcon class="h-5 w-5" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="fixed bottom-6 right-6">
      <button
        class="btn btn-circle btn-lg btn-primary"
        @click="dialogOpen = true"
      >
        <PlusIcon class="w-6 h-6" />
      </button>
    </div>

    <TagDialog v-model="dialogOpen" :edit="toEdit" @update="updateTag" />

    <Modal v-model="dialogDeleteOpen">
      <div class="modal-box">
        <h1 class="text-lg font-bold mb-2">{{ $t('tags.delete_title') }}</h1>
        <i18n-t keypath="tags.delete_confirm" tag="p">
          <TagLabel
            :name="toDelete?.name || ''"
            :color="toDelete?.color || ''"
          />
        </i18n-t>

        <div class="flex justify-end gap-2 mt-6">
          <button class="btn btn-primary" @click="dialogDeleteOpen = false">
            {{ $t('cancel') }}
          </button>
          <LoadingButton
            class="btn btn-error w-20"
            @click="deleteTag(toDelete)"
            :loading="deleteLoading"
          >
            {{ $t('delete') }}
          </LoadingButton>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script lang="ts" setup>
import TagDialog from '@/components/TagDialog.vue'
import LoadingButton from '@/components/ui/LoadingButton.vue'
import Modal from '@/components/ui/Modal.vue'
import TagLabel from '@/components/ui/Tag.vue'
import { useAuthStore } from '@/stores/auth'
import { Tag } from '@/types'
import { PencilIcon, PlusIcon, TrashIcon } from '@heroicons/vue/24/solid'
import { computed, onMounted, ref, watch } from 'vue'

const auth = useAuthStore()
const initialLoading = ref(true)
const tags = ref<Tag[]>([])
const query = ref('')

const dialogOpen = ref(false)
const dialogDeleteOpen = ref(false)
const deleteLoading = ref(false)
const toEdit = ref<Tag | null>(null)
const toDelete = ref<Tag | null>(null)

const filteredTags = computed(() => {
  return tags.value.filter((tag) => {
    return tag.name.toLowerCase().includes(query.value.toLowerCase())
  })
})

watch(dialogOpen, (value) => {
  if (!value) {
    toEdit.value = null
  }
})

function edit(tag: Tag) {
  toEdit.value = tag
}

function remove(tag: Tag) {
  toDelete.value = tag
  dialogDeleteOpen.value = true
}

function deleteTag(tag: Tag | null) {
  if (!tag) return

  deleteLoading.value = true
  auth.client
    .delete(`tags/${tag.id}`)
    .json()
    .then(() => {
      tags.value = tags.value.filter((t) => t.id !== tag.id)
    })
    .finally(() => {
      deleteLoading.value = false
      dialogDeleteOpen.value = false
    })
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
