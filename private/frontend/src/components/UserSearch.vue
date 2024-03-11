<script setup lang="ts">
import { computed, ref } from 'vue'
import { User } from '@/types.ts'
import { useAuthStore } from '@/stores/auth.ts'
import { XMarkIcon, UserPlusIcon } from '@heroicons/vue/24/outline'
import UserAvatar from '@/components/ui/UserAvatar.vue'

const users = ref<User[]>([])
const dialog = ref<HTMLDialogElement | null>(null)
const query = ref('')
const selectedPeople = ref<(User)[]>([])

const emit = defineEmits(['addUsers'])

const props = defineProps<{
  ignoreUsers?: User[]
}>()


const auth = useAuthStore()

const filteredPeople = computed(() => {
  const search = query.value.toLowerCase()

  return search === '' ? [] : users.value.filter((person) => {
    const name = `${person.first_name} ${person.last_name}`.toLowerCase()

    console.log(props.ignoreUsers)

    return name.includes(search) && !selectedPeople.value.includes(person) && !props.ignoreUsers?.find(u => u.id === person.id)
  })
})

function getUsers() {
  auth.client
    .get('users')
    .json()
    .then((res: any) => {
      console.log(res)
      users.value = res.users as User[]
    })
}

function openDialog() {
  dialog.value?.showModal()
}

function closeDialog() {
  dialog.value?.close()
}

function addUsers() {
  emit('addUsers', selectedPeople.value)
  dialog.value?.close()

  selectedPeople.value = []
}

function addSelectedUser(user: User) {
  selectedPeople.value.push(user)
  query.value = ''
}

getUsers()


</script>

<template>
  <div>
    <button
      @click="openDialog"
      class="btn btn-success btn-sm"
    >
      {{ $t('user_search.title') }}
    </button>
    <dialog class="modal" ref="dialog">
      <div class="modal-box flex flex-col max-h-full">
        <div class="modal-header">
          <div class="flex justify-end items-center">
            <button class="btn btn-square btn-ghost" @click="closeDialog">
              <XMarkIcon class="w-6 h-6" />
            </button>
          </div>
          <div class="flex flex-col justify-center items-center gap-2 relative -top-5">
            <h3 class="text-lg font-bold text-center">{{ $t('user_search.title') }}</h3>
            <UserPlusIcon class="w-8 h-8" />
          </div>
        </div>
        <div class="modal-body">
          <div v-if="selectedPeople">
            <div
              v-for="person in selectedPeople"
              :key="person.id"
              class="flex justify-around bg-primary bg-opacity-30 p-2 rounded-btn"
            >
              <div
                class="w-full flex flex-row items-center gap-2"
              >
                <UserAvatar :user="person" />
                <span class="">
                {{ person.first_name }} {{ person.last_name }}
              </span>
              </div>
              <button @click="selectedPeople.splice(selectedPeople.indexOf(person), 1)">
                <XMarkIcon class="w-4 h-4" />
              </button>
            </div>
            <div class="divider my-1"></div>
          </div>

          <input
            type="text"
            class="input input-bordered w-full my-2"
            :placeholder="$t('user_search.search_placeholder')"
            v-model="query"
          />

          <div class="flex flex-col gap-2">
            <div
              v-for="user in filteredPeople"
              :key="user.id"
              class="flex items-center gap-2 p-2 rounded-btn cursor-pointer hover:bg-primary hover:text-primary-content transition-colors duration-200 ease-out"
              @click="addSelectedUser(user)"
            >
              <UserAvatar :user="user" />
              <span class="">
                {{ user.first_name }} {{ user.last_name }}
              </span>
            </div>
          </div>
        </div>
        <div class="modal-bottom">
          <button
            class="btn btn-sm btn-success w-full"
            @click="addUsers"
            :disabled="selectedPeople.length === 0"
          >
            <slot name="button-text">{{ $t('user_search.selected_add') }}</slot>
          </button>
        </div>
      </div>
      <form method="dialog" class="modal-backdrop">
        <button>close</button>
      </form>
    </dialog>
  </div>
</template>

<style scoped>

</style>
