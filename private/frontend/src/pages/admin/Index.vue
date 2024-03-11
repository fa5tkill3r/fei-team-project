<script setup lang="ts">
import { useTeamStore } from '@/stores/team.ts'
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth.ts'
import { User } from '@/types.ts'
import UserAvatar from '@/components/ui/UserAvatar.vue'
import UserSearch from '@/components/UserSearch.vue'

const teamStore = useTeamStore()
const auth = useAuthStore()
const removeLoading = ref(false)

const users = ref<User[]>(teamStore.current?.users ?? [])
console.log(users.value)

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

function addUser(users: User[]) {
  auth.client
    .json({ users: users.map((u) => u.id) })
    .post(null, `teams/${teamStore.current?.id}/user`)
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
    <div
      v-if="teamStore.me?.role.user_add"
      class="flex justify-end">
      <UserSearch
        :ignore-users="teamStore.current?.users"
        @add-users="addUser"
      />
    </div>
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
          <td class="flex items-center gap-2">
            <UserAvatar size="md" :user="member" />
            <span>{{ member.first_name }} {{ member.last_name }}</span>
          </td>
          <td>{{ member.role.name }}</td>
          <td>
            <!--            <button class="btn btn-ghost">Edit</button>-->
            <button
              :disabled="removeLoading"
              v-if="teamStore.me?.role.user_add"
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
