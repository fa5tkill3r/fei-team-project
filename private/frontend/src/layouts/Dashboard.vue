<script setup lang="ts">
import { Bars3Icon } from '@heroicons/vue/24/solid'
</script>

<template>
  <div class="drawer lg:drawer-open">
    <input id="drawer" type="checkbox" class="drawer-toggle" />

    <div class="drawer-content flex flex-col">
      <div class="navbar bg-base-300 h-20 border-b border-base-content/10 sticky top-0 px-9">
        <label for="drawer" class="btn btn-square btn-ghost lg:hidden">
          <Bars3Icon class="w-6 h-6" />
        </label>
        <div class="flex-none">
          <input
            type="text"
            placeholder="Type here"
            class="input input-bordered w-full max-w-xs"
          />
        </div>

        <div class="flex-1">
          <ul class="menu menu-horizontal m-auto gap-2">
            <li><a>Link</a></li>
            <li><a>Link</a></li>
            <li><a>Link</a></li>
          </ul>
        </div>

        <div class="flex-none">
          <div class="dropdown dropdown-end">
            <div
              tabindex="0"
              role="button"
              class="btn btn-ghost btn-circle avatar"
            >
              <div class="w-10 rounded-full">
                <img
                  alt="Tailwind CSS Navbar component"
                  src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg"
                />
              </div>
            </div>
            <ul
              tabindex="0"
              class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-200 rounded-box w-52"
            >
              <li>
                <a class="justify-between">
                  Profile
                  <span class="badge">New</span>
                </a>
              </li>
              <li><a>Settings</a></li>
              <li><a>Logout</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="p-8">
        <slot></slot>
      </div>
    </div>

    <div class="drawer-side">
      <label
        for="drawer"
        aria-label="close sidebar"
        class="drawer-overlay"
      ></label>

      <ul
        class="menu w-80 min-h-full bg-base-300 text-base-content p-0 border-r border-base-content/10"
      >
        <div class="h-20 border-b border-base-content/10 flex items-center">
          <div class="flex items-center justify-between px-6 w-full">
            <h1 class="text-xl">Dashboard</h1>

            <button class="btn btn-ghost btn-square">
              <Bars3Icon class="w-6 h-6" />
            </button>
          </div>
        </div>

        <div class="p-4">
          <li><a>Sidebar Item 1</a></li>
          <li><a>Sidebar Item 2</a></li>

          <div class="divider"></div>
        </div>

        <div class="flex-1"></div>

        <div class="p-4">Version: 0.0.0</div>
      </ul>
    </div>
  </div>
</template>
