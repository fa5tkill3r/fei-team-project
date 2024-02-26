<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { Bars3Icon, PlusIcon } from '@heroicons/vue/24/solid'
import { ref } from 'vue'
import { useTeamStore } from '@/stores/team.ts'
import UserAvatar from '@/components/UserAvatar.vue'

const auth = useAuthStore()
const teamStore = useTeamStore()
const showTeams = ref(false)

function logout() {
  // TODO: add confirmation modal?
  auth.logout()
}
</script>

<template>
  <div class="drawer lg:drawer-open">
    <input id="drawer" type="checkbox" class="drawer-toggle" />

    <div class="drawer-content flex flex-col">
      <div
        class="navbar bg-base-300 h-20 border-b border-base-content/10 sticky top-0 xl:px-6 z-10"
      >
        <label for="drawer" class="btn btn-square btn-ghost lg:hidden">
          <Bars3Icon class="w-6 h-6" />
        </label>
        <div class="flex-none hidden md:block">
          <input
            type="text"
            :placeholder="$t('nav.search')"
            class="input input-bordered w-full max-w-xs"
          />
        </div>

        <div class="flex-1">
          <ul class="menu menu-horizontal m-auto gap-2 hidden md:flex">
            <li><a>Link</a></li>
            <li><a>Link</a></li>
            <li><a>Link</a></li>
          </ul>
        </div>

        <div class="flex-none">
          <label class="swap swap-rotate btn btn-ghost btn-circle">
            <!-- this hidden checkbox controls the state -->
            <input type="checkbox" class="theme-controller" value="light" />

            <!-- sun icon -->
            <svg
              class="swap-on fill-current w-8 h-8"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
            >
              <path
                d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"
              />
            </svg>

            <!-- moon icon -->
            <svg
              class="swap-off fill-current w-8 h-8"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
            >
              <path
                d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"
              />
            </svg>
          </label>

          <div class="dropdown dropdown-end">
            <UserAvatar
              v-if="auth.user"
              :user="auth.user"
              size="md"
              tabindex="0"
              role="button"
              class="btn btn-ghost btn-circle"
            />
            <ul
              tabindex="0"
              class="mt-2 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-200 rounded-box w-52"
            >
              <li class="disabled">
                <a>{{ $t('nav.profile') }}</a>
              </li>
              <li>
                <router-link :to="{ name: 'user-settings' }">
                  {{ $t('nav.settings') }}
                </router-link>
              </li>
              <li>
                <button @click="logout">
                  {{ $t('nav.logout') }}
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="px-5 py-3">
        <slot></slot>
      </div>
    </div>

    <div class="drawer-side">
      <label
        for="drawer"
        aria-label="close sidebar"
        class="drawer-overlay"
      ></label>

      <ul
        class="menu w-80 min-h-full bg-base-300 text-base-content p-0 border-r border-base-content/10"
      >
        <div class="h-20 border-b border-base-content/10 flex items-center">
          <div class="flex items-center justify-between px-6 w-full">
            <h1 class="text-xl">Dashboard</h1>

            <button class="btn btn-ghost btn-square lg:hidden">
              <Bars3Icon class="w-6 h-6" />
            </button>
          </div>
        </div>

        <div
          v-if="teamStore.current"
          class="collapse collapse-arrow bg-base-200 rounded-none"
          @click="showTeams = !showTeams"
        >
          <input type="checkbox" v-model="showTeams" />
          <div class="collapse-title text-xl font-medium">
            {{ teamStore.current.name }}
          </div>
          <div class="collapse-content space-y-2">
            <div class="w-full">
              <router-link
                to="/team/create"
                class="btn btn-sm btn-outline btn-block btn-primary"
              >
                <PlusIcon class="w-4 h-4" />
              </router-link>
            </div>

            <div v-for="team in teamStore.teams" class="w-full">
              <button
                class="btn btn-sm btn-block"
                :class="{ 'btn-accent': team.id === teamStore.current.id }"
                @click="teamStore.selectTeam(team.id)"
              >
                {{ team.name }}
              </button>
            </div>
          </div>
        </div>

        <div class="p-4">
          <li>
            <router-link :to="{ name: 'home' }">
              {{ $t('nav.tasks') }}
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'incidents' }">
              {{ $t('nav.incidents') }}
            </router-link>
          </li>

          <div class="divider"></div>
        </div>

        <div class="flex-1"></div>

        <div class="p-4">Version: 0.0.0</div>
      </ul>
    </div>
  </div>
</template>
