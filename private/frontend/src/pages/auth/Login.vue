<template>
  <div class="flex justify-center mt-32">
    <PageTitle text="auth.login" />

    <div class="max-w-lg w-full">
      <h1 class="text-3xl mb-4">
        {{ $t('auth.login') }}
      </h1>

      <form @submit.prevent="login">
        <label class="form-control w-full">
          <div class="label">
            <span class="label-text">E-Mail</span>
          </div>
          <input
            type="text"
            name="email"
            autocomplete="email"
            v-model="email"
            class="input input-bordered w-full"
          />
        </label>

        <label class="form-control w-full">
          <div class="label">
            <span class="label-text">{{ $t('auth.password') }}</span>
          </div>
          <input
            type="password"
            name="password"
            autocomplete="current-password"
            v-model="password"
            class="input input-bordered w-full"
          />
        </label>

        <div class="form-control">
          <label class="label cursor-pointer justify-start gap-2">
            <input
              type="checkbox"
              name="remember"
              v-model="remember"
              class="checkbox"
            />
            <span class="label-text">{{ $t('auth.remember') }}</span>
          </label>
        </div>

        <div class="text-right">
          <LoadingButton
            type="submit"
            class="btn btn-primary w-18"
            :loading="loading"
          >
            {{ $t('auth.login') }}
          </LoadingButton>
        </div>
      </form>
    </div>
  </div>
</template>

<script lang="ts" setup>
import LoadingButton from '@/components/ui/LoadingButton.vue'
import PageTitle from '@/components/utils/PageTitle.vue'
import { useAuthStore } from '@/stores/auth'
import { useTeamStore } from '@/stores/team'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const auth = useAuthStore()
const team = useTeamStore()
const email = ref('')
const password = ref('')
const remember = ref(false)
const loading = ref(false)

function login() {
  if (!email.value || !password.value) {
    return
  }

  loading.value = true
  auth
    .login(email.value, password.value, remember.value)
    .then(team.loadTeams)
    .then(() => {
      router.push({ name: 'home' })
    })
    .catch((err) => {
      console.log(err)
    })
    .finally(() => {
      loading.value = false
    })
}
</script>
