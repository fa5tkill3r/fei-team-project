<script setup lang='ts'>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Dashboard from './layouts/Dashboard.vue'
import { useAlertStore } from '@/stores/alert.ts'

const route = useRoute()
const Layout = computed(() => route.meta?.layout ?? Dashboard)

const alertStore = useAlertStore()
</script>

<template>
  <div class='fixed top-20 right-5 grid gap-4 z-[1000]'>
    <div
      v-for='alert in alertStore.alerts'
      role='alert' class='alert'
      :class="{
        'alert-success': alert.type === alertStore.AlertType.SUCCESS,
        'alert-error': alert.type === alertStore.AlertType.ERROR,
        'alert-warning': alert.type === alertStore.AlertType.WARNING,
        'alert-info': alert.type === alertStore.AlertType.INFO,
      }"
    >
      <!--      TODO: Add icon here -->
      <span>{{ alert.message }}</span>
    </div>

  </div>
  <Layout>
    <router-view></router-view>
  </Layout>
</template>
