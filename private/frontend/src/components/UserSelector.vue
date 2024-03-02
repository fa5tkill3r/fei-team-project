<template>
  <div>
    <button
      type="button"
      class="flex w-full justify-between text-sm hover:text-primary py-2 label-text"
      @click="openDialog"
    >
      <span>{{ $t('task.assignees') }}</span>
      <Cog6ToothIcon class="w-5 h-5" />
    </button>

    <div class="pb-2 text-base-content">
      <p v-if="!model || model.length === 0">
        {{ $t('task.no_assignees') }}
        <!-- <span class="link" @click="">{{ $t('task.assign-yourself') }}</span> -->
      </p>

      <div v-else class="flex flex-col gap-1">
        <div
          v-for="personId in model"
          :key="personId"
          class="flex items-center gap-2"
        >
          <UserAvatar :user="personMap[personId]" />
          <span>
            {{ personMap[personId].first_name }}
            {{ personMap[personId].last_name }}
          </span>
        </div>
      </div>
    </div>

    <!-- TODO: add fixed height -->
    <!-- TODO: add clear query -->
    <!-- TODO: add clear selection -->
    <dialog class="modal" ref="dialog">
      <div class="modal-box flex flex-col min-h-96 max-h-full">
        <h3 class="font-bold text-lg flex items-center justify-between mb-4">
          {{ $t('task.select_assignees') }}

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

        <div class="flex flex-col flex-1 gap-1.5 overflow-y-auto">
          <label
            v-for="person in filteredPeople"
            :key="person.id"
            class="flex items-center p-2 rounded-btn cursor-pointer hover:bg-primary hover:text-primary-content transition-colors duration-200 ease-out"
          >
            <input
              type="checkbox"
              v-model="tempModel"
              :value="person.id"
              class="hidden"
            />

            <span class="w-6">
              <CheckIcon v-if="tempModel.includes(person.id)" class="w-5 h-5" />
            </span>
            <UserAvatar :user="person" />

            <p class="ml-2">
              <!-- <span class="mr-1 font-medium">
                {{ person.email }}
              </span> -->
              <span>{{ person.first_name }} {{ person.last_name }}</span>
            </p>
          </label>

          <p v-if="filteredPeople.length === 0">
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

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Cog6ToothIcon, CheckIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { useTeamStore } from '@/stores/team'
import UserAvatar from './ui/UserAvatar.vue'

const team = useTeamStore()
const model = defineModel<number[]>()
const tempModel = ref<number[]>([])
const people = computed(() => team.members)
const dialog = ref<HTMLDialogElement | null>(null)
const query = ref('')
const personMap = computed(() =>
  Object.fromEntries(people.value.map((p) => [p.id, p])),
)

const filteredPeople = computed(() => {
  const search = query.value.toLowerCase()

  return search === ''
    ? people.value
    : people.value.filter((person) => {
        const name = `${person.first_name} ${person.last_name}`.toLowerCase()

        return name.includes(search)
      })
})

function openDialog() {
  dialog.value?.showModal()
  tempModel.value = model.value ? [...model.value] : []
}

function save() {
  model.value = [...tempModel.value]
  dialog.value?.close()
}
</script>
./ui/UserAvatar.vue
