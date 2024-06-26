<script setup lang="ts">
import UserSearch from '@/components/UserSearch.vue'
import Modal from '@/components/ui/Modal.vue'
import Select from '@/components/ui/Select.vue'
import UserAvatar from '@/components/ui/UserAvatar.vue'
import { AlertType, useAlertStore } from '@/stores/alert.ts'
import { useAuthStore } from '@/stores/auth.ts'
import { useTeamStore } from '@/stores/team.ts'
import { Role, User } from '@/types.ts'
import { ref } from 'vue'

const teamStore = useTeamStore()
const auth = useAuthStore()
const alertStore = useAlertStore()
const removeLoading = ref(false)
const userRemoveDialog = ref(false)
const userToBeRemoved = ref<User | null>(null)

const roles = ref<Role[]>([])

function removeUser(id: number) {
  userRemoveDialog.value = true

  userToBeRemoved.value =
    teamStore.current?.users.find((u) => u.id === id) || null
}

function removeUserConfirm(id: number) {
  removeLoading.value = true

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
    .finally(() => {
      removeLoading.value = false
      userRemoveDialog.value = false
      userToBeRemoved.value = null
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

function getRoles() {
  auth.client
    .get('roles')
    .json()
    .then(({ data }: any) => {
      roles.value = data as Role[]
      const test = roles.value.map((r) => r.name)
      console.log(test)
    })
    .catch((err) => {
      console.log(err)
    })
}

function updateRole(roleId: number, memberId: number) {
  auth.client
    .json({
      role_id: roleId,
      user_id: memberId,
    })
    .put(null, `teams/${teamStore.current?.id}/user/`)
    .json()
    .then(({ data }: any) => {
      teamStore.current!.users = data.users as User[]
      alertStore.addAlert('Role updated', AlertType.SUCCESS)
    })
    .catch((err) => {
      alertStore.addAlert(err.message, AlertType.ERROR)
    })
}

getRoles()
</script>

<template>
  <div>
    <div class="flex mb-3 gap-2">
      <router-link :to="{ name: 'incident-categories' }" class="btn">
        {{ $t('admin_panel.manage_incident_categories') }}
      </router-link>
      <router-link :to="{ name: 'incident-types' }" class="btn">
        {{ $t('admin_panel.manage_incident_types') }}
      </router-link>
    </div>

    <h1 class="text-xl">{{ $t('admin_panel.users') }}</h1>
    <div
      v-if="teamStore.me?.role.permissions.user_add"
      class="flex justify-end"
    >
      <UserSearch
        :ignore-users="teamStore.current?.users"
        @add-users="addUser"
      />
    </div>
    <table class="table w-full">
      <thead>
        <tr>
          <th>{{ $t('admin_panel.member') }}</th>
          <th>{{ $t('admin_panel.role') }}</th>
          <th>{{ $t('admin_panel.actions') }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="member in teamStore.current?.users">
          <td class="flex items-center gap-2">
            <UserAvatar size="md" :user="member" />
            <span>{{ member.first_name }} {{ member.last_name }}</span>
          </td>
          <td>
            <Select
              v-if="teamStore.me?.super_admin"
              v-model="member.role.name"
              :list="roles.map((r) => r.name)"
              @change="
                updateRole(
                  roles.find((r) => r.name === member.role.name)!.id,
                  member.id,
                )
              "
            />
            <span v-else>{{ member.role.name }} </span>
          </td>
          <!--        <td>{{ member.role.name }}</td>-->
          <td>
            <button
              :disabled="removeLoading"
              v-if="
                teamStore.me?.role.permissions.user_remove &&
                member.id !== teamStore.me?.id
              "
              class="btn btn-ghost"
              @click="removeUser(member.id)"
            >
              {{ $t('remove') }}
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="divider my-4"></div>

    <h1 class="text-xl">{{ $t('admin_panel.roles.roles') }}</h1>
    <div class="flex justify-end">
      <router-link :to="{ name: 'role-add' }" class="btn btn-sm btn-success">
        {{ $t('admin_panel.roles.add') }}
      </router-link>
    </div>
    <table class="table w-full">
      <thead>
        <tr>
          <th>{{ $t('admin_panel.role') }}</th>
          <th>{{ $t('admin_panel.roles.assigned_permissions') }}</th>
          <th v-if="teamStore.me?.super_admin">
            {{ $t('admin_panel.actions') }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="role in roles">
          <td>{{ role.name }}</td>
          <td>
            <ul>
              <li v-for="(permission, i) in role.permissions" :key="i">
                <span v-if="permission"
                  >{{ $t('admin_panel.roles.permissions.' + i) }}
                </span>
              </li>
            </ul>
          </td>
          <td v-if="teamStore.me?.super_admin" class="flex gap-2">
            <router-link
              :to="{ name: 'role-edit', params: { id: role.id } }"
              class="btn btn-ghost"
            >
              {{ $t('edit') }}
            </router-link>
          </td>
        </tr>
      </tbody>
    </table>

    <div>
      <Modal v-model="userRemoveDialog">
        <div class="modal-box flex flex-col">
          <h1 class="text-xl">{{ $t('admin_panel.remove_user') }}</h1>
          <i18n-t keypath="admin_panel.remove_user_description" tag="p">
            <b class="text-error"
              >{{ userToBeRemoved?.first_name }}
              {{ userToBeRemoved?.last_name }}</b
            >
          </i18n-t>
          <div class="flex justify-end gap-2">
            <button
              class="btn btn-ghost"
              :disabled="removeLoading"
              @click="removeLoading = false"
            >
              {{ $t('cancel') }}
            </button>
            <button
              class="btn btn-error"
              @click="removeUserConfirm(userToBeRemoved!.id)"
            >
              <span
                v-if="removeLoading"
                class="loading spinner spinner-white"
              />
              {{ $t('remove') }}
            </button>
          </div>
        </div>
      </Modal>
    </div>
  </div>
</template>

<style scoped></style>
