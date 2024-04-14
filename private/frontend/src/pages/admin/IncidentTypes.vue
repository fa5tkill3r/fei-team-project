<template>
  <div>
    <PageTitle :text="$t('admin_panel.incident_types')" />

    <h1 class="text-2xl mb-2 font-bold">
      {{ $t('admin_panel.incident_types') }}
    </h1>

    <div v-if="loading" class="flex justify-center items-center h-96">
      <div class="loading loading-spinner"></div>
    </div>

    <div v-else>
      <table class="table w-full">
        <thead>
          <tr>
            <th>{{ $t('admin_panel.id') }}</th>
            <th>{{ $t('admin_panel.name') }}</th>
            <th>{{ $t('admin_panel.actions') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="category in categories" :key="category.id">
            <td>{{ category.id }}</td>
            <td>
              <div v-if="editingCategoryId === category.id" class="flex gap-1">
                <input
                  type="text"
                  :value="category.name"
                  class="input input-sm input-bordered"
                />
                <button class="btn btn-sm" @click="save($event, category.id)">
                  {{ $t('save') }}
                </button>
              </div>
              <span v-else>{{ category.name }}</span>
            </td>
            <td>
              <div class="flex gap-1">
                <button
                  class="btn btn-sm btn-warning"
                  @click="editingCategoryId = category.id"
                >
                  {{ $t('admin_panel.edit') }}
                </button>
                <button
                  class="btn btn-sm btn-error"
                  @click="showDeleteDialog(category.id)"
                >
                  {{ $t('admin_panel.delete') }}
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="fixed bottom-6 right-6">
      <button
        class="btn btn-circle btn-lg btn-primary"
        @click="createDialog = true"
      >
        <PlusIcon class="w-6 h-6" />
      </button>
    </div>

    <ConfirmationDialog v-model="deleteDialog" @confirm="deleteCategory">
      <i18n-t keypath="admin_panel.delete_category">
        <span class="font-bold">{{ categoryToDelete?.name }}</span>
      </i18n-t>
    </ConfirmationDialog>

    <Modal v-model="createDialog">
      <div class="modal-box flex flex-col">
        <h1 class="text-xl font-bold mb-3">
          {{ $t('admin_panel.create_category') }}
        </h1>

        <div>
          <label class="form-control w-full">
            <div class="label">
              <span class="label-text">
                {{ $t('admin_panel.name') }}
              </span>
            </div>
            <input
              type="text"
              class="input input-bordered w-full"
              v-model="newCategoryName"
            />
          </label>
        </div>

        <div class="flex justify-end mt-4 gap-2">
          <button class="btn" @click="createDialog = false">
            {{ $t('cancel') }}
          </button>
          <LoadingButton
            class="btn btn-primary"
            :loading="createLoading"
            @click="create"
          >
            {{ $t('create') }}
          </LoadingButton>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script lang="ts" setup>
import ConfirmationDialog from '@/components/ConfirmationDialog.vue'
import LoadingButton from '@/components/ui/LoadingButton.vue'
import Modal from '@/components/ui/Modal.vue'
import PageTitle from '@/components/utils/PageTitle.vue'
import { useAuthStore } from '@/stores/auth'
import { IncidentCategory } from '@/types'
import { PlusIcon } from '@heroicons/vue/24/solid'
import { computed, onMounted, ref } from 'vue'

const auth = useAuthStore()
const loading = ref(true)
const createLoading = ref(false)
const categories = ref<IncidentCategory[]>([])
const editingCategoryId = ref<number | null>(null)
const deleteCategoryId = ref<number | null>(null)
const newCategoryName = ref('')
const createDialog = ref(false)
const deleteDialog = ref(false)
const categoryToDelete = computed(() => {
  return categories.value.find(
    (category) => category.id === deleteCategoryId.value,
  )
})

function deleteCategory() {
  if (deleteCategoryId.value) {
    auth.client
      .delete(`incident-types/${deleteCategoryId.value}`)
      .json()
      .then(() => {
        categories.value = categories.value.filter(
          (category) => category.id !== deleteCategoryId.value,
        )
        deleteDialog.value = false
      })
  }
}

function create() {
  if (!newCategoryName.value) {
    return
  }

  createLoading.value = true
  auth.client
    .post({ name: newCategoryName.value }, 'incident-types')
    .json()
    .then(({ data }: any) => {
      categories.value.push(data)
      newCategoryName.value = ''
      createDialog.value = false
    })
    .finally(() => {
      createLoading.value = false
    })
}

function save(event: MouseEvent, id: number) {
  // @ts-ignore
  const name = event.target?.previousElementSibling?.value

  if (!name) {
    return
  }

  auth.client
    .put({ name }, `incident-types/${id}`)
    .json()
    .then(() => {
      const index = categories.value.findIndex((c) => c.id === id)

      editingCategoryId.value = null
      categories.value[index].name = name
    })
}

function loadCategories() {
  auth.client
    .get('incident-types')
    .json()
    .then(({ data }: any) => {
      categories.value = data
    })
    .finally(() => {
      loading.value = false
    })
}

function showDeleteDialog(categoryId: number) {
  deleteCategoryId.value = categoryId
  deleteDialog.value = true
}

onMounted(() => {
  loadCategories()
})
</script>
