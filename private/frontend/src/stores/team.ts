import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { Team, User } from '@/types'

export const useTeamStore = defineStore('team', () => {
  const authStore = useAuthStore()
  const current = ref<Team | null>(null)
  const teams = ref<Team[]>([])
  const members = computed<User[]>(() => current?.value?.users ?? [])
  const me = computed<User | null>(() => current?.value?.users.find((u) => u.id === authStore.user?.id) ?? null)

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
    return getTeams().then((teams: Team[]) => {
      if (teams.length > 0) {
        current.value = teams[0]
      }
    })
  }

  function selectTeam(teamId: number) {
    current.value = teams.value.find((t) => t.id === teamId) ?? null
  }

  return { teams, current, members, me, selectTeam, loadTeams }
})
