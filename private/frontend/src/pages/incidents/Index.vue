<script setup lang="ts">
import Dropdown from '@/components/ui/Dropdown.vue'
import DropdownButton from '@/components/ui/dropdown/DropdownButton.vue'
import { debounce } from '@/lib/utils'
import { useAuthStore } from '@/stores/auth'
import { Incident } from '@/types'
import { ChevronDownIcon, PlusIcon, XMarkIcon } from '@heroicons/vue/24/solid'
import { computed, onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const router = useRouter()
const route = useRoute()
const auth = useAuthStore()
const loading = ref(true)
const filterHelpOpen = ref(false)
const incidents = ref<Incident[]>([])
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
    <PageTitle text="nav.incidents" />

    <div>
      <h1 class="font-bold text-2xl">{{ $t('main.incidents') }}</h1>

      <div class="mt-4 flex gap-2 max-w-6xl">
        <div class="join w-full relative">
          <!-- TODO: search -->
          <Dropdown class="left-0 menu-sm">
            <template #button>
              <button class="btn join-item w-28">
                {{ $t('filters') }}
                <ChevronDownIcon class="w-4 h-4" />
              </button>
            </template>

            <DropdownButton
              v-for="filter in filters"
              :key="filter.label"
              class="dropdown-item"
              @click="getFilteredIncidences(filter.value)"
            >
              {{ $t(`incidents.filter.${filter.label}`) }}
            </DropdownButton>
          </Dropdown>

          <input
            v-model="search"
            type="text"
            class="input input-bordered w-full join-item"
            @input="loadIncidentsDebounced"
          />

          <div
            v-if="filterHelpOpen"
            class="absolute top-12 mt-2 bg-base-200 rounded-lg p-4 w-full z-10"
          >
            <p v-html="$t('task.filter_docs')"></p>

            <button
              class="btn btn-sm absolute top-0 right-0 mt-2 mr-2"
              @click="filterHelpOpen = false"
            >
              <XMarkIcon class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="loading" class="flex justify-center w-full mt-4">
      <div class="loading"></div>
    </div>

    <div v-else-if="incidents.length" class="flex flex-col mt-4">
      <div>
        <div class="overflow-x-auto">
          <table class="table w-full">
            <thead>
              <tr>
                <th>{{ $t('incidents.title') }}</th>
                <th>{{ $t('incidents.description') }}</th>
                <th>{{ $t('incidents.reporter') }}</th>
                <th>{{ $t('incidents.reported') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="incident in incidents" :key="incident.id" class="h-14">
                <td>
                  <router-link
                    :to="{
                      name: 'incident-detail',
                      params: { id: incident.id },
                    }"
                  >
                    {{ incident.title }}
                  </router-link>
                </td>
                <td>
                  <p>{{ incident.description }}</p>
                </td>
                <td>
                  <p>{{ incident.email }}</p>
                </td>
                <td>
                  <p>{{ $d(new Date(incident.created_at), 'short') }}</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div v-else class="flex justify-center w-full mt-4">
      <p>{{ $t('incident.no_incidents') }}</p>
    </div>

    <div class="fixed bottom-6 right-6">
      <router-link
        class="btn btn-circle btn-lg btn-primary"
        :to="{ name: 'incidents-create' }"
      >
        <PlusIcon class="w-6 h-6" />
      </router-link>
    </div>
  </div>
</template>
