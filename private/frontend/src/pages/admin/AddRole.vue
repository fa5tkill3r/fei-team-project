<script setup lang="ts">
import { ref } from 'vue'

const role = ref<any>({
  name: '',
})

</script>

<template>
  <div>
    <h1>{{ $t('admin_panel.roles.add') }}</h1>
    <form>
      <div class="md:flex md:items-center mb-6">
        <label>
          Role name:
          AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
        </label>
        <input
          type="text"
          class="input input-bordered"
          v-model="role.name"
        />
        <button class="btn btn-primary" type="submit">
          {{ $t('admin_panel.roles.add') }}
        </button>
      </div>
    </form>
  </div>
</template>

<style scoped>

</style>
