<template>
  <Modal v-model="model">
    <div class="modal-box">
      <h1 class="text-xl font-bold mb-3">
        {{ $t('incidents.add_report') }}
      </h1>

      <div class="grid grid-cols-1 gap-4">
        <label class="form-control">
          <div class="label">
            <span class="label-text">
              {{ $t('incidents.report_date') }}
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
            <span class="label-text">{{ $t('incidents.description') }}</span>
          </div>
          <textarea
            v-model="incidentReport.description"
            type="text"
            :placeholder="$t('incidents.description_placeholder')"
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
import { IncidentChronologically } from '@/types'
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
    return new Date(incidentReport.value.date).toISOString()
  },
  set(newValue: string): void {
    incidentReport.value.date = format(newValue, 'yyyy-MM-dd HH:mm:ss')
  },
});

const incidentReport = ref<IncidentChronologically>({
  date: format(Date.now(), 'yyyy-MM-dd HH:mm:ss'),
  description: '',
  id: null,
})

function saveIncidentReport() {
  loading.value = true
  return auth.client
    .post(incidentReport.value, `incident-chronologically/${route.params.id}`)
    .json()
    .then(() => {
      model.value = false
      incidentReport.value = {
        date: format(Date.now(), 'yyyy-MM-dd HH:mm:ss'),
        description: '',
        id: null,
      }
      emit('reload')
    }).finally(() => {
      loading.value = false
    })
}
</script>
