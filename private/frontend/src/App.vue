<script setup lang="ts">
import HelloWorld from './components/HelloWorld.vue'
</script>

<template>
  <div>
    <h1 class="text-3xl font-bold underline">
      Hello world!
    </h1>
    <button
      class="inline-block cursor-pointer rounded-md bg-gray-800 px-4 py-3 text-center text-sm font-semibold uppercase text-white transition duration-200 ease-in-out hover:bg-gray-900">
      Button
    </button>
    <a href="https://vitejs.dev" target="_blank">
      <img src="/vite.svg" class="logo" alt="Vite logo" />
    </a>
    <a href="https://vuejs.org/" target="_blank">
      <img src="./assets/vue.svg" class="logo vue" alt="Vue logo" />
    </a>
  </div>
  <div>
    <div class="flex flex-col">
      <div class="form-control w-52">
        <label class="cursor-pointer label">
          <span class="label-text">Remember me</span>
          <input type="checkbox" class="toggle toggle-primary" checked />
        </label>
      </div>
      <div class="form-control w-52">
        <label class="cursor-pointer label">
          <span class="label-text">Remember me</span>
          <input type="checkbox" class="toggle toggle-secondary" checked />
        </label>
      </div>
      <div class="form-control w-52">
        <label class="cursor-pointer label">
          <span class="label-text">Remember me</span>
          <input type="checkbox" class="toggle toggle-accent" checked />
        </label>
      </div>
    </div>
  </div>
  <HelloWorld msg="Vite + Vue" />
</template>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
