<script setup lang="ts">
import { useTeamStore } from '@/stores/team.ts'
import { computed, ref } from 'vue'
import { useAuthStore } from '@/stores/auth.ts'
import { Team, User } from '@/types.ts'

const teamStore = useTeamStore()
const auth = useAuthStore()
const me = computed(() => teamStore.getMe())
const removeLoading = ref(false)

function removeUser(id: number) {
  auth.client
    .json({ user_id: id })
    .delete(`teams/${teamStore.current?.id}/user`)
    .json()
    .then(({ data }: any) => {
      teamStore.current!.users = data.users as User[]
    })
    .catch((err) => {
      console.log(err)
    })
}
</script>

<template>
  <div>
    <h1 class="text-xl">Manage access</h1>
    <!--  table of team members-->
    <table class="table w-full">
      <thead>
        <tr>
          <th>Member</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="member in teamStore.current?.users">
          <td>
            <UserAvatar size="md" :user="member" />
            <span>{{ member.first_name }} {{ member.last_name }}</span>
          </td>
          <td>{{ member.role.name }}</td>
          <td>
            <!--            <button class="btn btn-ghost">Edit</button>-->
            <button
              :disabled="removeLoading"
              v-if="me?.role.user_add"
              class="btn btn-ghost"
              @click="removeUser(member.id)"
            >
              Remove
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped></style>
