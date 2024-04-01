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

                    <div>
                        <span class="text-sm label-text">{{ $t('incident.description') }}</span>
                        <div>{{ incident.description }}</div>

                    </div>
                </div>

                <div class="divider mt-1 mb-3 col-span-full"></div>

                <div class="col-span-12 lg:col-span-9">
                    <span class="text-sm label-text">{{ $t('incident.type') }}</span>
                    <div>{{ incident.type }}</div>
                </div>

                <div class="col-span-12 lg:col-span-3 text-base-content">
                    
                    <div class="flex justify-end mb-7">
                        <button class="btn btn-circle" @click="showDialog = true">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100"
                                viewBox="1 1 32 66">
                                <path
                                    d="M 16 3 C 8.832031 3 3 8.832031 3 16 C 3 23.167969 8.832031 29 16 29 C 23.167969 29 29 23.167969 29 16 C 29 8.832031 23.167969 3 16 3 Z M 16 5 C 22.085938 5 27 9.914063 27 16 C 27 22.085938 22.085938 27 16 27 C 9.914063 27 5 22.085938 5 16 C 5 9.914063 9.914063 5 16 5 Z M 15 10 L 15 12 L 17 12 L 17 10 Z M 15 14 L 15 22 L 17 22 L 17 14 Z">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <h4 class="text-lg font-semibold text-base-content mb-1">{{ $t('incident.details') }}</h4>

                    <div class="divider mt-1 mb-3 col-span-full"></div>

                    <div>
                        <span class="text-sm label-text">{{ $t('incident.creator') }}</span>
                        <div>{{ incident.name }} {{ incident.surname }}</div>
                    </div>

                    <div>
                        <span class="text-sm label-text">{{ $t('incident.creator_email') }}</span>
                        <div>{{ incident.email }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="incident" class="flex justify-end mt-7">
            <button class="btn" @click="showCreateTask = true">
                Create task from incident
            </button>
        </div>
    </div>

    <BasicTaskCreateDialog v-model="showCreateTask" />
    <IncidentDetailDialog v-model="showDialog" />
</template>

<script setup lang="ts">
import PageTitle from '@/components/utils/PageTitle.vue'
import { useAuthStore } from '@/stores/auth'
import { Incident } from '@/types'
import { onMounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import IncidentDetailDialog from '@/components/IncidentDetailDialog.vue'
import BasicTaskCreateDialog from '@/components/BasicTaskCreateDialog.vue'

const auth = useAuthStore()
const route = useRoute()
const incident = ref<Incident | null>(null)

const loading = ref(false)
const closeLoading = ref(false)

const showDialog = ref(false)
const showCreateTask = ref(false)

function loadIncident() {
    incident.value = null
    return auth.client
        .get(`incidents/${route.params.id}`)
        .json()
        .then((res: any) => {
            incident.value = res.data as Incident
        })
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
        .then(() => {
            loading.value = false
        })
})
</script>