<script setup lang="ts">
import UserAvatar from '@/components/ui/UserAvatar.vue'
import { useAuthStore } from '@/stores/auth'
import { useTeamStore } from '@/stores/team.ts'
import {
  ArrowTopRightOnSquareIcon,
  BeakerIcon,
  BookOpenIcon,
  BriefcaseIcon,
  Cog6ToothIcon,
  MoonIcon,
  QuestionMarkCircleIcon,
  ShieldExclamationIcon,
  SunIcon,
  UserIcon,
} from '@heroicons/vue/24/outline'
import { Bars3Icon, PlusIcon } from '@heroicons/vue/24/solid'
import { ref } from 'vue'

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

        <div class="flex-1">
          <!-- <ul class="menu menu-horizontal m-auto gap-2 hidden md:flex">
            <li><a>Link</a></li>
            <li><a>Link</a></li>
            <li><a>Link</a></li>
          </ul> -->
        </div>

        <div class="flex-none">
          <label class="swap swap-rotate btn btn-ghost btn-circle">
            <!-- this hidden checkbox controls the state -->
            <input type="checkbox" class="theme-controller" value="light" />

            <!-- sun icon -->
            <SunIcon class="swap-on w-8 h-8" />

            <!-- moon icon -->
            <MoonIcon class="swap-off w-8 h-8" />
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
                <a>
                  <UserIcon class="w-5 h-5" />
                  {{ $t('nav.profile') }}
                </a>
              </li>
              <li>
                <router-link :to="{ name: 'user-settings' }">
                  <Cog6ToothIcon class="w-5 h-5" />
                  {{ $t('nav.settings') }}
                </router-link>
              </li>
              <div class="divider my-0"></div>
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
            <router-link to="/">
              <h1 class="text-xl">
                {{ $t('dashboard') }}
              </h1>
            </router-link>

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
              <BriefcaseIcon class="w-5 h-5" />
              {{ $t('nav.tasks') }}
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'incidents' }">
              <ShieldExclamationIcon class="w-5 h-5" />
              {{ $t('nav.incidents') }}
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'theses' }">
              <BeakerIcon class="w-5 h-5" />
              {{ $t('nav.theses') }}
            </router-link>
          </li>
          <li>
            <router-link :to="{ name: 'library' }">
              <BookOpenIcon class="w-5 h-5" />
              {{ $t('nav.library') }}
            </router-link>
          </li>
          <li>
            <a href="#">
              <QuestionMarkCircleIcon class="w-5 h-5" />
              {{ $t('nav.wiki') }}
              <ArrowTopRightOnSquareIcon class="w-5 h-5" />
            </a>
          </li>

          <div class="divider"></div>
        </div>

        <div class="flex-1"></div>

        <div class="p-4">Version: 0.0.0</div>
      </ul>
    </div>
  </div>
</template>
