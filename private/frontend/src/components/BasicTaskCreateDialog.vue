<template>
  <Modal v-model="model">
    <div class="modal-box overflow-visible">
      <h1 class="text-xl font-bold mb-3">
        {{ $t('incidents.create_task') }}
      </h1>

      <div class="grid grid-cols-[min-content_auto] auto-cols-max gap-4">
        <label class="label" for="taskName">
          <span class="label-text">
            {{ $t('task.title') }}
          </span>
        </label>
        <input
          id="taskName"
          v-model="taskDetail.name"
          type="text"
          class="input input-bordered"
        />

        <label class="label" for="taskSeverity">
          <span class="label-text">
            {{ $t('task.severity') }}
          </span>
        </label>
        <Select
          id="taskSeverity"
          :list="['low', 'medium', 'high']"
          :model-value="taskDetail.severity"
          @change="taskDetail.severity = $event"
          class="w-full"
        ></Select>
      </div>

      <div class="flex justify-end mt-4">
        <button
          type="button"
          class="btn btn-secondary mr-2"
          @click="model = false"
        >
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
import { useTeamStore } from '@/stores/team'
import { defineModel, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Modal from './ui/Modal.vue'
import Select from './ui/Select.vue'

const model = defineModel<boolean>()

const route = useRoute()
const router = useRouter()

const auth = useAuthStore()
const team = useTeamStore()

const taskDetail = {
  name: '',
  severity: 'low',
  incident_id: route.params.id,
}

const id = ref()

function createTask() {
  auth.client
    .post(taskDetail, `tasks/${team.current?.id}`)
    .json()
    .then((res: any) => {
      id.value = res.data.id
      model.value = false
      router.push({ name: 'task-detail', params: { id: res.data.id } })
    })
}
</script>
