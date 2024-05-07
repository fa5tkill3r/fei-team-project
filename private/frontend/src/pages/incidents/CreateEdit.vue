<template>
    <form
      @submit.prevent="saveIncident"
      class="grid grid-cols-12 gap-x-4 gap-y-4 max-w-7xl mx-auto"
      v-if="!initialLoading"
    >
      <PageTitle :text="edit ? 'task.editing' : 'task.new'" :task="incident.name" />

      <div class="col-span-full">
        <label class="form-control w-full">
          <div class="label">
            <span class="label-text">{{ $t('task.title') }}</span>
          </div>
          <input
            type="text"
            v-model="incident.title"
            :placeholder="$t('task.title_placeholder')"
            class="input input-bordered w-full"
          />
        </label>

        <label class="form-control">
          <div class="label">
            <span class="label-text">{{ $t('task.description') }}</span>
          </div>
          <textarea
            class="textarea textarea-bordered h-24 text-base"
            :placeholder="$t('task.description_placeholder')"
            v-model="incident.description"
          ></textarea>
        </label>

        <label class="form-control">
          <div class="label">
            <span class="label-text">Typ incidentu</span>
          </div>
          <combobox
            v-model="incident.type"
            :options="incidentTypes.map((i) => ( i ))"
            placeholder="Vyber typ incidentu"
          />
        </label>

        <div class="mt-5">
          <div class="flex items-center justify-between">
            <span class="text-lg font-semibold">
              Nahlasujuci
            </span>
            <button
              type="button"
              class="btn btn-outline btn-primary ml-2"
              @click="fillMe"
            >
              Predvyplnit mna
            </button>
          </div>
          <div class="divider my-0"></div>
        </div>
        <div>
          <label class="form-control w-full">
            <div class="label">
              <span class="label-text">Meno:</span>
            </div>
            <input
              type="text"
              v-model="incident.name"
              placeholder=""
              class="input input-bordered w-full"
            />
          </label>
          <label class="form-control w-full">
            <div class="label">
              <span class="label-text">Priezvisko:</span>
            </div>
            <input
              type="text"
              v-model="incident.surname"
              placeholder=""
              class="input input-bordered w-full"
            />
          </label>

          <label class="form-control w-full">
            <div class="label">
              <span class="label-text">AIS ID:</span>
            </div>
            <input
              type="text"
              v-model="incident.ais_id"
              placeholder=""
              class="input input-bordered w-full"
            />
          </label>
          <label class="form-control w-full">
            <div class="label">
              <span class="label-text">Email:</span>
            </div>
            <input
              type="text"
              v-model="incident.email"
              placeholder=""
              class="input input-bordered w-full"
            />
          </label>
          <label class="form-control w-full">
            <div class="label">
              <span class="label-text">Telefonne cislo:</span>
            </div>
            <input
              type="text"
              v-model="incident.phone_number"
              placeholder=""
              class="input input-bordered w-full"
            />
          </label>
        </div>

        <div class="text-right hidden lg:block">
          <LoadingButton
            type="submit"
            class="btn btn-primary mt-4"
            :loading="loading"
          >
            {{ $t('task.save') }}
          </LoadingButton>
        </div>
      </div>

<!--      <div class="col-span-12 xl:col-span-3">-->
<!--        <label class="form-control">-->
<!--          <div class="label">-->
<!--            <span class="label-text">{{ $t('task.severity') }}</span>-->
<!--          </div>-->
<!--          &lt;!&ndash; TODO: add better styles and figure out what values are actually supposed to be here &ndash;&gt;-->
<!--          <select-->
<!--            class="select select-bordered select-sm w-full max-w-xs"-->
<!--            v-model="task.severity"-->
<!--          >-->
<!--            <option>low</option>-->
<!--            <option>medium</option>-->
<!--            <option>high</option>-->
<!--          </select>-->
<!--        </label>-->

<!--        <div class="divider my-0"></div>-->

<!--        <div class="form-control">-->
<!--          <div class="label">-->
<!--            <span class="label-text">{{ $t('task.deadline') }}</span>-->
<!--          </div>-->

<!--          &lt;!&ndash; TODO: add better styles &ndash;&gt;-->
<!--          <DatePicker-->
<!--            v-model="task.deadline"-->
<!--            :enable-time-picker="false"-->
<!--            auto-apply-->
<!--          />-->
<!--        </div>-->

<!--        <div class="divider my-0"></div>-->

<!--        <TagSelector v-model="task.tags" />-->

<!--        <div class="divider my-0"></div>-->
<!--        <div>-->
<!--          <UserSelector v-model="task.users" />-->
<!--          <div class="form-control">-->
<!--            <label class="label cursor-pointer">-->
<!--              <span class="label-text">{{ $t('task.all_users') }}</span>-->
<!--              <input v-model="task.all_users" type="checkbox" class="checkbox" />-->
<!--            </label>-->
<!--          </div>-->
<!--        </div>-->

<!--        <div class="divider my-0"></div>-->

<!--        <TaskSelector v-model="task.parent" :task-id="id" />-->
<!--      </div>-->

      <div class="col-span-12 xl:col-span-9 text-right lg:hidden">
        <LoadingButton
          type="submit"
          class="btn btn-primary mt-4"
          :loading="loading"
        >
          {{ $t('task.save') }}
        </LoadingButton>
      </div>
    </form>
  </template>

  <script setup lang="ts">
  import TagSelector from '@/components/TagSelector.vue'
  import TaskSelector from '@/components/TaskSelector.vue'
  import UserSelector from '@/components/UserSelector.vue'
  import LoadingButton from '@/components/ui/LoadingButton.vue'
  import { useAuthStore } from '@/stores/auth'
  import { useTeamStore } from '@/stores/team'
  import { IncidentRequest, TaskRequest } from '@/types'
  import DatePicker from '@vuepic/vue-datepicker'
  import { onMounted, ref } from 'vue'
  import { useRouter } from 'vue-router'

  import '@vuepic/vue-datepicker/dist/main.css'
  import Combobox from '@/components/ui/Combobox.vue'

  const { edit, id } = defineProps<{ edit?: boolean; id?: number }>()
  const router = useRouter()
  const auth = useAuthStore()
  const team = useTeamStore()
  const loading = ref(false)
  const initialLoading = ref(true)
  const incident = ref<IncidentRequest>({
    title: '',
    description: '',
    type: '',
    name: '',
    surname: '',
    ais_id: '',
    email: '',
    phone_number: '',
  })
  const incidentTypes = ref<string[]>([])

  function loadIncidentTypes() {
    auth.client
      .get(`incident-types/`)
      .json()
      .then((res: any) => {
        incidentTypes.value = res.data.map((i: any) => i.name)
      })
  }

  function loadTask() {
    initialLoading.value = true
    auth.client
      .get(`tasks/${team.current?.id}/${id}`)
      .json()
      .then((res: any) => {
        task.value = {
          name: res.data.name,
          description: res.data.description,
          severity: res.data.severity,
          deadline: res.data.deadline,
          users: res.data.users.map((u: any) => u.id),
          tags: res.data.tags.map((t: any) => t.id),
          parent: res.data.parent?.id,
        }
      })
      .finally(() => {
        initialLoading.value = false
      })
  }

  function saveIncident() {
    loading.value = true

    const request = edit
      ? auth.client.put(incident.value, `incidents/${id}`)
      : auth.client.post(incident.value, `incidents`)

    request
      .json()
      .then((res: any) => {
        router.push({ name: 'incident-detail', params: { id: res.data.id } })
      })
      .finally(() => {
        loading.value = false
      })
  }

  function fillMe() {
    incident.value.name = auth.user?.first_name || ''
    incident.value.surname = auth.user?.last_name || ''
    incident.value.ais_id = ''
    incident.value.email = auth.user?.email || ''
    incident.value.phone_number = ''
  }

  loadIncidentTypes()

  onMounted(() => {
    if (edit && id) {
      loadTask()

    } else {
      initialLoading.value = false
    }
  })
  </script>
