import { ref } from 'vue'
import { defineStore } from 'pinia'
import wretch from 'wretch'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const client = ref(wretch(import.meta.env.VITE_API_URL))
  let timerId: number | undefined

  // TODO: implement login
  function login() {
    timerId = setInterval(() => {
      // TODO: refresh access token
    })
  }

  function logout() {
    clearInterval(timerId)

    user.value = null
    client.value = wretch(import.meta.env.VITE_API_URL)
  }

  return { user, client, login, logout }
})
