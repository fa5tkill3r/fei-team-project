<template>
    <Modal v-model="model">
      <div class="modal-box">
        <h1 class="text-xl font-bold mb-3">
            {{$t('incident_detail.title') }} 
        </h1>
  
        <div class="grid grid-cols-1 gap-4">
          <div v-for="(field, label) in fields" :key="label" class="form-control">
            <label class="label">
              <span class="label-text">
                {{ $t(field.label) }}
              </span>
              <input v-model="incidentDetailUpdate[field.prop]" type="text" class="input input-bordered" />
            </label>
          </div>
        </div>
  
        <div class="flex justify-end mt-4">
          <button type="button" class="btn btn-secondary mr-2" @click="model = false">
            {{ $t('cancel') }}
          </button>
          <button type="submit" class="btn btn-primary" @click="saveIncidentDetail">
            {{ $t('submit') }}
          </button>
        </div>
      </div>
    </Modal>
  </template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { onMounted, defineModel, ref } from 'vue'
import { useRoute } from 'vue-router'
import { IncidentDetail } from '@/types'
import Modal from './ui/Modal.vue'

const initialLoading = ref(true)

const model = defineModel<boolean>()

const route = useRoute()

const auth = useAuthStore()

const incidentDetail = ref<IncidentDetail | null>(null)

const fields = {
  attacked_service: { label: 'incidentDetail.attaced_service', prop: 'attacked_service' },
  attack_severity: { label: 'incidentDetail.attack_sevirity', prop: 'attack_severity' },
  attack_solution: { label: 'incidentDetail.attack_solution', prop: 'attack_solution' },
  attack_description: { label: 'incidentDetail.attack_description', prop: 'attack_description' },
  attack_vector: { label: 'incidentDetail.attack_vector', prop: 'attack_vector' },
  attack_category: { label: 'incidentDetail.attack_category', prop: 'attack_category' },
  attack_type: { label: 'incidentDetail.attack_type', prop: 'attack_type' },
  security_measures: { label: 'incidentDetail.security_measures', prop: 'security_measures' },
  notes: { label: 'incidentDetail.notes', prop: 'notes' }
}

type NullableStringObject = {
    [key: string]: string | undefined;
};

const incidentDetailUpdate: NullableStringObject = {
    attacked_service: undefined,
    attack_severity: undefined,
    attack_solution: undefined,
    attack_description: undefined,
    attack_vector: undefined,
    attack_category: undefined,
    attack_type: undefined,
    security_measures: undefined,
    notes: undefined,
};

function saveIncidentDetail() {
    console.log(incidentDetailUpdate)
    return auth.client
        .put(incidentDetailUpdate, `incident-info/${route.params.id}`   )
        .json()
        .then(() => {
            model.value = false;
            loadIncidentDetail();
        });
}

function loadIncidentDetail() {
    incidentDetail.value = null
    return auth.client
        .get(`incident-info/${route.params.id}`)
        .json()
        .then((res: any) => {
            incidentDetail.value = res.data 
            incidentDetailUpdate.attacked_service = incidentDetail.value?.attacked_service
            incidentDetailUpdate.attack_severity = incidentDetail.value?.attack_severity
            incidentDetailUpdate.attack_solution = incidentDetail.value?.attack_solution
            incidentDetailUpdate.attack_description = incidentDetail.value?.attack_description
            incidentDetailUpdate.attack_vector = incidentDetail.value?.attack_vector
            incidentDetailUpdate.attack_category = incidentDetail.value?.attack_category
            incidentDetailUpdate.attack_type = incidentDetail.value?.attack_type
            incidentDetailUpdate.security_measures = incidentDetail.value?.security_measures
            incidentDetailUpdate.notes = incidentDetail.value?.notes
            
        })
}

onMounted(() => {
    loadIncidentDetail()
        .then(() => {
            initialLoading.value = false
        })

})




</script>
