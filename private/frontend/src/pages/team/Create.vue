<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth.ts'
import { AlertType, useAlertStore } from '@/stores/alert.ts'
import { useRouter } from 'vue-router'
import { useTeamStore } from '@/stores/team.ts'

const auth = useAuthStore()
const teamStore = useTeamStore()
const alertStore = useAlertStore()
const router = useRouter()

interface Team {
  name: string
  description: string
  users: string[]
}

const team = ref<Team>({
  name: '',
  description: '',
  users: [],
})

const loading = ref(false)

function createTeam() {
  loading.value = true
  auth.client
    .post(team.value, 'teams')
    .json()
    .then(() => {
      alertStore.addAlert('Team created', AlertType.SUCCESS)
      teamStore.loadTeams()

      router.push({ name: 'home' })
    })
    .catch(() => {
      alertStore.addAlert('Error', AlertType.ERROR)
    })
    .finally(() => {
      loading.value = false
    })
}
</script>

<template>
  <div>
    <form @submit.prevent="createTeam">
      <label class="form-control w-full">
        <div class="label">
          <span class="label-text">Team Name</span>
        </div>
        <input
          type="text"
          v-model="team.name"
          placeholder="Type here"
          class="input input-bordered w-full"
        />
      </label>
      <label class="form-control w-full">
        <div class="label">
          <span class="label-text">Description</span>
        </div>
        <input
          type="text"
          v-model="team.description"
          placeholder="Team description"
          class="input input-bordered w-full"
        />
      </label>

      <button type="submit" class="btn btn-primary mt-4" :disabled="false">
        <span v-if="loading" class="loading loading-spinner"></span>
        <span v-if="loading">Loading...</span>
        <span v-else>Create Team</span>
      </button>
    </form>
  </div>
</template>

<style scoped></style>
