<template>
  <Menu as="div" class="relative">
    <MenuButton>
      <slot name="button"></slot>
    </MenuButton>

    <transition
      enter-active-class="transition duration-100 ease-out origin-top"
      enter-from-class="transform scale-95"
      enter-to-class="transform scale-100"
      leave-active-class="transition duration-75 ease-out"
      leave-from-class="transform scale-100 opacity-100"
      leave-to-class="transform scale-95 opacity-0"
    >
      <MenuItems
        as="ul"
        class="absolute z-[1] right-0 mt-2 menu bg-base-200 rounded-box w-52"
        v-bind="$attrs"
      >
        <slot> </slot>
      </MenuItems>
    </transition>
  </Menu>
</template>

<script setup lang="ts">
import { Menu, MenuButton, MenuItems } from '@headlessui/vue'

defineOptions({
  inheritAttrs: false,
})
</script>
