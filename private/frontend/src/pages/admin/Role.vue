<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth.ts'
import { useRoute, useRouter } from 'vue-router'

const role = ref<string>('')
const permissions = ref<string[]>([])


const auth = useAuthStore()
const router = useRouter()
const route = useRoute()
const categories = ref<Category[]>([])

interface Category {
  name: string
  permissions: string[]
}

function addOrUpdateRole() {
  const newRole: { [key: string]: any } = {
    name: role.value,
    slug: role.value.toLowerCase(),
  }
  permissions.value.forEach((permission) => {
    newRole[permission] = true
  })

  categories.value.forEach((category) => {
    category.permissions.forEach((permission) => {
      if (!permissions.value.includes(permission)) {
        newRole[permission] = false
      }
    })
  })

  const promise = route.params.id
    ? auth.client
      .json(newRole)
      .put(null,'roles/' + route.params.id)
    : auth.client
      .json(newRole)
      .post( null, 'roles')

  promise.json()
    .then(() => {
      router.push({ name: 'admin' })
    })

  // auth.client
  //   .json(newRole)
  //   .post(null, 'roles')
  //   .json()
  //   .then(() => {
  //     router.push({ name: 'admin' })
  //   })
  //   .catch((err) => {
  //     console.log(err)
  //   })
}

function getRole() {
  auth.client
    .get('roles/' + route.params.id)
    .json()
    .then((res: any) => {
      role.value = res.role.name
      for (const [key, value] of Object.entries(res.role)) {
        if (value === true) {
          permissions.value.push(key)
        }
      }
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
  createCategory('Team', [
    'team_info',
  ])
}

initCategories()
getRole()

</script>

<template>
  <div>
    <h1 class="text-xl font-bold">
      <span v-if="!route.params.id">{{ $t('admin_panel.roles.add') }}</span>
      <span v-else>{{ $t('admin_panel.roles.edit') }}</span>
    </h1>
    <form @submit.prevent="addOrUpdateRole">
      <div class="flex flex-col sm:flex-row gap-1 justify-center items-center">
        <label>
          {{ $t('admin_panel.roles.name') }}
        </label>
        <input
          type="text"
          class="input input-bordered flex-grow"
          v-model="role"
        />
        <button class="btn btn-primary w-full sm:w-fit" type="submit">
          <span v-if="!route.params.id">{{ $t('admin_panel.roles.add') }}</span>
          <span v-else>{{ $t('admin_panel.roles.edit') }}</span>
        </button>
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
