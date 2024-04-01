<script setup lang="ts">
import Dropdown from '@/components/ui/Dropdown.vue'
import { debounce } from '@/lib/utils'
import { useAuthStore } from '@/stores/auth'
import { Incident } from '@/types'
import {
  ChevronDownIcon,
  ListBulletIcon,
  PlusIcon,
  Squares2X2Icon,
  XMarkIcon,
} from '@heroicons/vue/24/solid'
import { computed, onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const router = useRouter()
const route = useRoute()
const auth = useAuthStore()
const loading = ref(true)
const filterHelpOpen = ref(false)
const incidents = ref<Incident[]>([])
const viewType = ref('board')
const search = ref((route.query.q as string | null) ?? '')
const state = ref('new')
const filters = computed(() => [
  { label: 'new', value: 'new' },
  { label: 'in_progress', value: 'in_progress' },
  { label: 'closed', value: 'closed' },
])

function loadIncidents() {
  loading.value = true
  auth.client
    .query({
      search: search.value,
      status: state.value,
    })
    .get('incidents')
    .json()
    .then((res: any) => {
      incidents.value = res.data
    })
    .catch((err) => {
      console.log(err)
    })
    .finally(() => {
      loading.value = false
    })
}

function getFilteredIncidences(filter: string) {
  state.value = filter
  console.log(state.value)
  loadIncidents()
}

function loadIncidentsAndUpdateQuery() {
  router.replace({ query: { q: search.value || undefined } })
  loadIncidents()
}

const loadIncidentsDebounced = debounce(loadIncidentsAndUpdateQuery, 400)

watch(
  () => route.query,
  () => {
    search.value = (route.query.q as string | null) ?? ''
    loadIncidents()
  },
)

onMounted(() => {
  loadIncidents()
})
</script>

<template>
  <div>
    <PageTitle text="nav.tasks" />

    <div>
      <h1 class="font-bold text-2xl">{{ $t('main.tasks') }}</h1>

      <div class="join mt-4">
        <button class="btn join-item" @click="viewType = 'board'">
          <Squares2X2Icon class="w-5 h-5" />
          {{ $t('main.board') }}
        </button>
        <button class="btn join-item" @click="viewType = 'list'">
          <ListBulletIcon class="w-5 h-5" />
          {{ $t('main.list') }}
        </button>
      </div>

      <div class="mt-4 flex gap-2 max-w-6xl">
        <div class="join w-full relative">
          <Dropdown class="left-0 menu-sm">
            <template #button>
              <button class="btn join-item w-28">
                {{ $t('incidents.filter') }}
                <ChevronDownIcon class="w-4 h-4" />
              </button>
            </template>

            <button v-for="filter in filters" :key="filter.label" class="dropdown-item"
              @click="getFilteredIncidences(filter.value)">
              {{ $t(`incidents.filter.${filter.label}`) }}
            </button>
          </Dropdown>

          <input v-model="search" type="text" class="input input-bordered w-full join-item"
            @input="loadIncidentsDebounced" />

          <div v-if="filterHelpOpen" class="absolute top-12 mt-2 bg-base-200 rounded-lg p-4 w-full z-10">
            <p v-html="$t('task.filter_docs')"></p>

            <button class="btn btn-sm absolute top-0 right-0 mt-2 mr-2" @click="filterHelpOpen = false">
              <XMarkIcon class="w-4 h-4" />
            </button>
          </div>
        </div>

        <router-link :to="{ name: 'tags' }" class="btn">
          {{ $t('task.manage_tags') }}
        </router-link>
      </div>
    </div>

    <div v-if="loading" class="flex justify-center w-full mt-4">
      <div class="loading"></div>
    </div>

    <div v-else-if="incidents.length" class="flex flex-col mt-4">
      <masonry v-if="viewType === 'board'" :cols="4" :gutter="15">
        <router-link v-for="incident in incidents" class="block bg-base-300 p-6 rounded-lg mb-4"
          :to="{ name: 'incident-detail', params: { id: incident.id } }">
          <div class="mb-2 flex justify-between">
            <p class="font-semibold text-xl">{{ incident?.title }}</p>
          </div>
          <div class="mb-1 flex justify-end">
            <p class="text-sm text-base-500">{{ incident?.created_at }}</p>
          </div>
          
        </router-link>
      </masonry>

      <div v-if="viewType === 'list'">
        <div class="overflow-x-auto">
          <table class="table w-full">
            <thead>
              <tr>
                <th>{{ $t('incident.title') }}</th>
                <th>{{ $t('incidnet.description')}}</th>
                <th>{{ $t('incident.created_at') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="incident in incidents" :key="incident.id" class="h-14">
                <td>
                  <router-link :to="{ name: 'incident-detail', params: { id: incident.id } }">
                    {{ incident.title }}
                  </router-link>
                </td>
                <td>
                  <p>{{ incident.description }}</p>
                </td>
                <td>
                  <p>{{ incident.created_at }}</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div v-else class="flex justify-center w-full mt-4">
      <p>{{ $t('task.no_tasks') }}</p>
    </div>

    <div class="fixed bottom-6 right-6">
      <router-link class="btn btn-circle btn-lg btn-primary" :to="{ name: 'task-create' }">
        <PlusIcon class="w-6 h-6" />
      </router-link>
    </div>
  </div>
</template>
