import wretch from 'wretch'
import { ref } from 'vue'
import { defineStore } from 'pinia'
import { useRouter, useRoute } from 'vue-router'
import { tryParseJSON } from '@/lib/utils'
import { queryStringAddon } from 'wretch/addons'

const TOKEN_REFRESH_BUFFER = 30 * 1000

const defaultClient = wretch(import.meta.env.VITE_API_URL)
  .addon(queryStringAddon)
  .accept('application/json')
  .resolve((r) => r.json())

function parseJWT(token: string) {
  const start = token.indexOf('.') + 1
  const end = token.indexOf('.', start)
  const base64Url = token.substring(start, end)
  const base64 = base64Url.replace('-', '+').replace('_', '/')

  return tryParseJSON<any>(window.atob(base64))
}

export const useAuthStore = defineStore('auth', () => {
  let timerId: number | undefined

  const user = ref(null)
  const client = ref(defaultClient)
  const router = useRouter()
  const route = useRoute()

  function handleUserResponse(res: any) {
    if (!res) {
      return Promise.reject()
    }

    const { exp } = parseJWT(res.token)
    client.value = defaultClient.auth(`Bearer ${res.token}`)

    if (res.user) {
      user.value = res.user
    }

    if (timerId) {
      clearTimeout(timerId)
    }
    if (!exp) {
      return Promise.reject()
    }

    // refresh token 30 seconds before it expires
    const sleep = exp * 1000 - Date.now() - TOKEN_REFRESH_BUFFER
    timerId = setTimeout(() => {
      tokenRefresh()
    }, sleep)

    return Promise.resolve()
  }

  function tokenRefresh() {
    return client.value
      .post(null, 'users/refresh')
      .catch((err) => {
        // TODO: handle error
        console.log(err)
      })
      .then(handleUserResponse)
  }

  function login(
    email?: string,
    password?: string,
    rememberMe: boolean = false,
  ) {
    return defaultClient
      .options({ credentials: 'include' })
      .post({ email, password, remember_me: rememberMe }, 'users/login')
      .then(handleUserResponse)
      .then(() => {
        const redirect = route.query.redirect || '/'

        if (typeof redirect === 'string') {
          router.push(redirect)
        }
      })
  }

  function register(user: {
    email: string
    password: string
    firstName: string
    lastName: string
  }) {
    return defaultClient
      .post(
        {
          email: user.email,
          password: user.password,
          first_name: user.firstName,
          last_name: user.lastName,
        },
        'users/register',
      )
      .then(handleUserResponse)
      .then(() => {
        router.push('/')
      })
  }

  function logout() {
    return client.value
      .options({ credentials: 'include' })
      .post(null, 'users/logout')
      .then(() => {
        clearInterval(timerId)

        user.value = null
        client.value = defaultClient

        router.push({ name: 'login' })
      })
  }

  return { user, client, register, login, logout }
})
