import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'

export const useTeamStore = defineStore('team', () => {
  const authStore = useAuthStore()

  const team = ref<any>(null)
  const teams = ref<any[]>([])

  let callbacks: any[] = []

  async function getTeams() {
    return authStore.client
      .get('teams')
      .then((res: any) => {
        teams.value = res.data
      })
  }

  getTeams().then(() => {
    if (teams.value.length > 0) {
      team.value = teams.value[0]
    }
  })

  team.value = teams.value[0]

  function selectTeam(teamId: number) {
    team.value = teams.value.find((t: any) => t.id === teamId)

    callbacks.forEach((callback) => callback(team.value))
  }


  function onTeamChange(callback: (callback: any) => void) {
    callbacks.push(callback)
  }

  return { teams, team, selectTeam, onTeamChange }
})
