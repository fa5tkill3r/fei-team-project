<template>
  <div>
    <form @submit.prevent="createTask">
      <label class="form-control w-full">
        <div class="label">
          <span class="label-text">{{ $t('task.title') }}</span>
        </div>
        <input
          type="text"
          v-model="task.title"
          placeholder="Type here"
          class="input input-bordered w-full"
        />
      </label>

      <label class="form-control">
        <div class="label">
          <span class="label-text">{{ $t('task.description') }}</span>
        </div>
        <textarea
          class="textarea textarea-bordered h-24 text-base"
          placeholder="Bio"
        ></textarea>
      </label>

      <UsersCombobox v-model="task.users" />

      <button type="submit" class="btn btn-primary mt-4">
        {{ $t('task.create') }}
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { ref } from 'vue'
import UsersCombobox from '@/components/UsersCombobox.vue';

interface Task {
  title: string
  description: string
  users: string[]
  tags: string[]
}

const auth = useAuthStore()
const task = ref<Task>({
  title: '',
  description: '',
  users: [],
  tags: [],
})

function createTask() {
  console.log(task.value)

  auth.client.post(task.value, 'tasks')
}
</script>
