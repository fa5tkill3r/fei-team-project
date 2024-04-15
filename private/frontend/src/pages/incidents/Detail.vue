<template>
  <div class="max-w-7xl mx-auto">
    <div v-if="loading" class="flex justify-center items-center h-96">
      <div class="loading loading-spinner"></div>
    </div>

    <div v-else-if="incident" class="grid grid-cols-12 gap-x-4">
      <PageTitle :text="`${incident.title} #${incident.id}`" />

      <div class="col-span-full flex justify-between">
        <div>
          <h1 class="text-2xl font-bold text-base-content mb-1">
            {{ incident.title }}
            <span class="text-base-content/65">#{{ incident.id }}</span>
          </h1>
        </div>

        <div>
          <a
            :href="`http://localhost:8000/api/generate-pdf/${incident.id}`"
            class="btn btn-sm btn-ghost btn-square"
          >
            <DocumentArrowDownIcon class="w-5 h-5" />
          </a>
        </div>
      </div>

      <div class="divider mt-1 mb-3 col-span-full"></div>

      <div class="col-span-12 lg:col-span-9">
        <div class="rounded-lg border border-base-content/10 w-full">
          <div
            class="border-b border-base-content/10 px-4 py-2 text-sm label-text"
          >
            <span class="font-bold mr-1">
              {{ incident.name }}
              {{ incident.surname }}
            </span>
            <span>
              {{
                $t('incidents.reported_at', {
                  distance: formatDistance(incident.created_at),
                })
              }}
            </span>
          </div>

          <div
            class="bg-base-300/30 px-4 pt-2 pb-3 rounded-b-lg text-base-content"
          >
            <div
              v-if="incident.description"
              class="prose max-w-none text-base-content"
              v-html="incident.description"
            ></div>
            <div v-else class="prose max-w-none italic text-base-content">
              {{ $t('incidents.no_description') }}
            </div>

            <div v-if="incident.images" class="flex gap-2 mt-2">
              <vue-easy-lightbox
                :visible="galleryVisible"
                :imgs="incident.images"
                :index="galleryIndex"
                @hide="galleryVisible = false"
              >
                <template #loading>
                  <div class="absolute top-[50%]">
                    <div class="loading loading-spinner"></div>
                  </div>
                </template>
              </vue-easy-lightbox>

              <div v-for="(image, i) in incident.images">
                <img
                  class="w-24 h-24 rounded-md object-cover cursor-pointer"
                  :src="image"
                  :key="image"
                  @click="showImage(i)"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-span-12 lg:col-span-3 text-base-content">
        <div>
          <span class="text-sm label-text">{{ $t('incidents.reporter') }}</span>
          <div>{{ incident.name }} {{ incident.surname }}</div>
          <div>{{ incident.email }}</div>
        </div>

        <div>TODO: add additional info</div>

        <button class="btn mt-3" @click="showCreateTask = true">
          Create task from incident
        </button>
        <button class="btn mt-1" @click="showInfoDialog = true">
          Add info
        </button>
      </div>
    </div>
  </div>

  <BasicTaskCreateDialog v-model="showCreateTask" />
  <IncidentDetailDialog v-model="showInfoDialog" />
</template>

<script setup lang="ts">
import BasicTaskCreateDialog from '@/components/BasicTaskCreateDialog.vue'
import IncidentDetailDialog from '@/components/IncidentDetailDialog.vue'
import PageTitle from '@/components/utils/PageTitle.vue'
import { useFormatDistance } from '@/composables/useFormatDistance'
import { useAuthStore } from '@/stores/auth'
import { Incident } from '@/types'
import { DocumentArrowDownIcon } from '@heroicons/vue/24/solid'
import { onMounted, ref, watch } from 'vue'
import VueEasyLightbox from 'vue-easy-lightbox'
import { useRoute } from 'vue-router'

const auth = useAuthStore()
const route = useRoute()
const incident = ref<Incident | null>(null)
const formatDistance = useFormatDistance()

const loading = ref(true)
const closeLoading = ref(false)
const showInfoDialog = ref(false)
const showCreateTask = ref(false)
const galleryVisible = ref(false)
const galleryIndex = ref(0)

function loadIncident() {
  incident.value = null
  return auth.client
    .get(`incidents/${route.params.id}`)
    .json()
    .then((res: any) => {
      incident.value = res.data as Incident
    })
    .finally(() => {
      loading.value = false
    })
}

function showImage(index: number) {
  galleryIndex.value = index
  galleryVisible.value = true
}

closeLoading.value = true

watch(
  () => route.params.id,
  () => {
    loadIncident()
  },
)

onMounted(() => {
  loadIncident()
})
</script>
