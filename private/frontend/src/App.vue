<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Dashboard from './layouts/Dashboard.vue'

const route = useRoute()
const Layout = computed(() => route.meta?.layout ?? Dashboard)
</script>

<template>
  <Layout>
    <router-view></router-view>
  </Layout>
</template>
