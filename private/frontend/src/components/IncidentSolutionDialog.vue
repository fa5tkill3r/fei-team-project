<template>
  <Modal v-model="model">
    <div class="modal-box">
      <h1 class="text-xl font-bold mb-3">
        {{ $t('incidents.add_solution') }}
      </h1>

      <div class="grid grid-cols-1 gap-4">
        <label class="form-control">
          <div class="label">
            <span class="label-text">
              {{ $t('incidents.solution_deadline') }}
            </span>
          </div>
          <DatePicker
            v-model="date"
            :enable-time-picker="true"
            auto-apply
          />
        </label>

        <label class="form-control">
          <div class="label">
            <span class="label-text">{{ $t('incidents.title') }}</span>
          </div>
          <input
            v-model="incidentSolution.name"
            type="text"
            :placeholder="$t('incidents.title_placeholder')"
            class="input input-bordered"
          />
        </label>

        <label class="form-control">
          <div class="label">
            <span class="label-text">{{ $t('incidents.responsible_person') }}</span>
          </div>
          <input
            v-model="incidentSolution.name_of_responsible_person"
            type="text"
            :placeholder="$t('incidents.responsible_person_placeholder')"
            class="input input-bordered"
          />
        </label>

        <label class="form-control">
          <div class="label">
            <span class="label-text">{{ $t('incidents.solution_description') }}</span>
          </div>
          <textarea
            v-model="incidentSolution.description"
            type="text"
            :placeholder="$t('incidents.solution_description_placeholder')"
            class="input input-bordered h-24"
          />
        </label>
      </div>

      <div class="flex justify-end mt-4 items-center">
        <button type="button" class="btn mr-2" @click="model = false">
          {{ $t('cancel') }}
        </button>
        <LoadingButton
          type="submit"
          class="btn btn-primary"
          @click="saveIncidentReport"
          :loading="loading"
        >
          {{ $t('submit') }}
        </LoadingButton>
      </div>
    </div>
  </Modal>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { defineModel, ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import { IncidentSolution } from '@/types'
import Modal from './ui/Modal.vue'
import DatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import { format } from 'date-fns'
import LoadingButton from '@/components/ui/LoadingButton.vue'

const loading = ref(false)

const emit = defineEmits(['reload'])

const model = defineModel<boolean>()
const route = useRoute()
const auth = useAuthStore()

const date = computed({
  get(): string {
    return new Date(incidentSolution.value.deadline).toISOString()
  },
  set(newValue: string): void {
    incidentSolution.value.deadline = format(newValue, 'yyyy-MM-dd HH:mm:ss')
  },
});

const incidentSolution = ref<IncidentSolution>({
  deadline: format(Date.now(), 'yyyy-MM-dd HH:mm:ss'),
  description: '',
  id: null,
  name: '',
  name_of_responsible_person: '',
})

function saveIncidentReport() {
  loading.value = true
  return auth.client
    .post(incidentSolution.value, `incident-solutions/${route.params.id}`)
    .json()
    .then(() => {
      model.value = false
      incidentSolution.value = {
        deadline: format(Date.now(), 'yyyy-MM-dd HH:mm:ss'),
        description: '',
        id: null,
        name: '',
        name_of_responsible_person: '',
      }
      emit('reload')
    }).finally(() => {
      loading.value = false
    })
}
</script>
