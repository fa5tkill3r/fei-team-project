<script setup lang="ts">
import { IncidentSolution } from '@/types'
import { EllipsisHorizontalIcon } from '@heroicons/vue/24/solid'
import { computed, ref } from 'vue'

const props = defineProps<{
  solution: IncidentSolution
}>()

const solution = computed(() => props.solution)
const deleteLoading = ref(false)
// const editLoading = ref(false) TODO: Implement edit functionality
const edit = ref<boolean>(false)

const emit = defineEmits<{
  edit: [{ id: number; text: string }]
  delete: [id: number]
}>()

function deleteSolution() {
  deleteLoading.value = true
  emit('delete', solution.value.id!)
}

// TODO: Implement edit functionality
//function editComment() {
  // editLoading.value = true
  // emit('editComment', { id: incidentCh.value.id, text: editText.value })
// }
</script>

<template v-if="incidentCh">
  <div class="flex gap-2">
    <div class="rounded-lg border border-base-content/10 w-full">
      <div
        class="border-b border-base-content/10 px-4 py-2 text-sm label-text flex justify-between items-center"
      >
        <span>
          <div class="grid grid-cols-2 gap-2">
            <b class="col-span-full text-error">{{solution.name}}</b>
            <ul class="col-span-1">
              <li>{{$t('incidents.responsible_person')}}:</li>
              <li>{{$t('incidents.deadline')}}:</li>
            </ul>
            <ul class="col-span-1">
              <li><b>{{solution.name_of_responsible_person}}</b></li>
              <li><b>{{solution.deadline}}</b></li>
            </ul>
          </div>
        </span>
        <div class="dropdown dropdown-end">
          <button class="btn btn-xs btn-ghost">
            <EllipsisHorizontalIcon class="w-5 h-5" />
          </button>
          <ul
            tabindex="0"
            class="p-2 shadow menu dropdown-content bg-base-100 rounded-box w-52"
          >
            <li>
              <button :disabled="edit" @click="edit = !edit">
                {{ $t('edit') }}
              </button>
            </li>
            <li>
              <button
                :class="deleteLoading ? 'ml-3 loading loading-sm' : ''"
                @click="deleteSolution"
              >
                {{ $t('delete') }}
              </button>
            </li>
          </ul>
        </div>
      </div>

      <div
        class="prose max-w-none bg-base-300/30 px-4 pt-2 pb-2.5 rounded-b-lg text-base-content"
      >
        <span>{{ solution.description }}</span>
      </div>
    </div>
  </div>
</template>
