<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth.ts'
import { useRouter } from 'vue-router'

const role = ref<string>('')
const permissions = ref<string[]>([])


const auth = useAuthStore()
const router = useRouter()
const categories = ref<Category[]>([])

interface Category {
  name: string
  permissions: string[]
}

function addRole() {
  const newRole: { [key: string]: any } = {
    name: role.value,
    slug: role.value.toLowerCase(),
  }
  permissions.value.forEach((permission) => {
    newRole[permission] = true
  })

  auth.client
    .json(newRole)
    .post(null, 'roles')
    .json()
    .then(() => {
      router.push({ name: 'admin' })
    })
    .catch((err) => {
      console.log(err)
    })
}

function initCategories() {
  const createCategory = (name: string, permissions: string[]) => {
    categories.value.push({
      name,
      permissions,
    })
  }

  createCategory('Tasks', [
    'task_access',
    'task_create',
    'task_delete',
  ])
  createCategory('User', [
    'user_access',
    'user_add',
    'user_remove',
  ])
  createCategory('Roles', [
    'role_access',
    'role_add',
    'role_delete',
  ])
  createCategory('Team', [
    'team_info',
  ])
}

initCategories()
</script>

<template>
  <div>
    <h1 class="text-xl font-bold">{{ $t('admin_panel.roles.add') }}</h1>
    <form @submit.prevent="addRole">
      <div class="flex flex-col sm:flex-row">
        <div class="flex-grow flex-row gap-5">
          <label>
            Role name:
          </label>
          <input
            type="text"
            class="input input-bordered w-full sm:w-fit"
            v-model="role"
          />
          <button class="btn btn-primary w-full sm:w-fit" type="submit">
            {{ $t('admin_panel.roles.add') }}
          </button>
        </div>

      </div>


      <div
        class="grid grid-cols-1 md:grid-cols-3"
      >
        <div v-for="category in categories"
             class="flex flex-col items-center mt-5"
        >
          <h2
            class="text-center text-xl font-bold"
          >{{ category.name }}</h2>
          <ul class="form-control">
            <li v-for="permission in category.permissions">
              <label class="label cursor-pointer gap-2">
                <span class="label-text">{{ $t('admin_panel.roles.permissions.' + permission) }}</span>
                <input type="checkbox" class="toggle toggle-secondary" v-model="permissions" :value="permission">
              </label>
            </li>
          </ul>
        </div>
      </div>
    </form>
  </div>
</template>

<style scoped>

</style>
