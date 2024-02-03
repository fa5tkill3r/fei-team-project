import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'

export interface Team {
  id: number
  name: string
  description: string
  created_at: string
  updated_at: string
}

export const useTeamStore = defineStore('team', () => {
  const authStore = useAuthStore()

  const team = ref<Team | undefined>()
  const teams = ref<Team[]>([])

  let callbacks: any[] = []

  function getTeams() {
    return authStore.client.get('teams').then((res: any) => {
      teams.value = res.data

      return res.data
    })
  }

  function loadTeams() {
    return getTeams().then((teams) => {
      if (teams.length > 0) {
        team.value = teams[0]
      }
    })
  }

  function selectTeam(teamId: number) {
    team.value = teams.value.find((t: any) => t.id === teamId)

    callbacks.forEach((callback) => callback(team.value))
  }

  function onTeamChange(callback: (callback: any) => void) {
    callbacks.push(callback)
  }

  return { teams, team, selectTeam, loadTeams, onTeamChange }
})
