<template>
    <Modal v-model="model">
      <div class="modal-box">
        <h1 class="text-xl font-bold mb-3">
          {{  $t('incident.create_task') }}
        </h1>
  
        <div class="grid grid-cols-1 gap-4">
            <label class="label">
              <span class="label-text">
                {{ $t('task.title') }}
              </span>
              <input v-model="taskDetail.name" type="text" class="input input-bordered" />
            </label>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <label class="label">
              <span class="label-text">
                {{ $t('task.severity') }}
              </span>
              <input v-model="taskDetail.severity" type="text" class="input input-bordered" />
            </label>
        </div>


        <div class="flex justify-end mt-4">
          <button type="button" class="btn btn-secondary mr-2" @click="model = false">
            {{ $t('cancel') }}
          </button>
          <button type="submit" class="btn btn-primary" @click="createTask">
            {{ $t('submit') }}
          </button>
        </div>

      </div>
    </Modal>
  </template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import {useTeamStore} from '@/stores/team'
import { defineModel, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Modal from './ui/Modal.vue'

const model = defineModel<boolean>()

const route = useRoute()
const router = useRouter()

const auth = useAuthStore()
const team = useTeamStore()

const taskDetail = {
    name: "",
    severity: "",    
    incident_id: route.params.id,
}

const id = ref()

function createTask(){
    auth.client.post(taskDetail, `tasks/${team.current?.id}`)
    .json()
    .then((res: any) => {
        id.value = res.data.id
        model.value = false
        router.push({ name: 'task-detail', params: { id: res.data.id } })
    })


}

</script>
