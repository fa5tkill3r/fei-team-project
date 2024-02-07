import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { computed } from 'vue'
import { Team, User } from '@/types'

export const useTeamStore = defineStore('team', () => {
  const authStore = useAuthStore()
  const current = ref<Team | undefined>()
  const teams = ref<Team[]>([])
  const members = computed<User[]>(() => current?.value?.users ?? [])

  function getTeams() {
    return authStore.client
      .get('teams')
      .json()
      .then((res: any) => {
        teams.value = res.data

        return res.data
      })
  }

  function loadTeams() {
    return getTeams().then((teams) => {
      if (teams.length > 0) {
        current.value = teams[0]
      }
    })
  }

  function selectTeam(teamId: number) {
    current.value = teams.value.find((t) => t.id === teamId)
  }

  return { teams, current, members, selectTeam, loadTeams }
})
