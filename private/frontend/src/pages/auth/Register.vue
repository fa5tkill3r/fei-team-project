<template>
  <div>
    <h1>Register</h1>

    <form @submit.prevent="register">
      <input
        type="text"
        name="email"
        autocomplete="email"
        v-model="form.email"
      />
      <input type="text" name="firstName" v-model="form.firstName" />
      <input type="text" name="lastName" v-model="form.lastName" />
      <input
        type="password"
        name="password"
        autocomplete="new-password"
        v-model="form.password"
      />

      <button type="submit">Register</button>
    </form>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const form = ref({
  email: '',
  firstName: '',
  lastName: '',
  password: '',
})

function register() {
  if (
    !form.value.email ||
    !form.value.firstName ||
    !form.value.lastName ||
    !form.value.password
  ) {
    return
  }

  auth.register(form.value).catch((err) => {
    console.log(err)
  })
}
</script>
