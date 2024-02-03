<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { Bars3Icon, PlusIcon } from '@heroicons/vue/24/solid'
import { ref } from 'vue'
import { useTeamStore } from '@/stores/team.ts'

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
        class="navbar bg-base-300 h-20 border-b border-base-content/10 sticky top-0 xl:px-6"
      >
        <label for="drawer" class="btn btn-square btn-ghost lg:hidden">
          <Bars3Icon class="w-6 h-6" />
        </label>
        <div class="flex-none hidden md:block">
          <input
            type="text"
            placeholder="Type here"
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
          <div class="dropdown dropdown-end">
            <div
              tabindex="0"
              role="button"
              class="btn btn-ghost btn-circle avatar"
            >
              <div class="w-10 rounded-full">
                <img
                  alt="Tailwind CSS Navbar component"
                  src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg"
                />
              </div>
            </div>
            <ul
              tabindex="0"
              class="mt-2 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-200 rounded-box w-52"
            >
              <li class="disabled">
                <a>Profile</a>
              </li>
              <li class="disabled">
                <a>Settings</a>
              </li>
              <li>
                <button @click="logout">Logout</button>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="p-8">
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
          v-if="teamStore.team"
          class="collapse collapse-arrow bg-base-200 rounded-none"
          @click="showTeams = !showTeams"
        >
          <input type="checkbox" v-model="showTeams" />
          <div class="collapse-title text-xl font-medium">
            {{ teamStore.team.name }}
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
                :class="{ 'btn-accent': team.id === teamStore.team.id }"
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
          <li><a>Sidebar Item 2</a></li>

          <div class="divider"></div>
        </div>

        <div class="flex-1"></div>

        <div class="p-4">Version: 0.0.0</div>
      </ul>
    </div>
  </div>
</template>
