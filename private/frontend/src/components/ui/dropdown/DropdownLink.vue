<script setup lang="ts">
import { MenuItem } from '@headlessui/vue'
import { RouterLink, useLink } from 'vue-router'

const props = defineProps({
  // @ts-ignore
  ...RouterLink.props,
})
// @ts-ignore
const linkProps = useLink(props)

console.log(linkProps)

function handleLinkClick(close: () => void) {
  linkProps.navigate()
  close()
}
</script>

<template>
  <MenuItem
    v-if="props.to"
    as="li"
    :class="{ disabled: props.disabled }"
    v-slot="{ close }"
  >
    <a :href="linkProps.href.value" @click.prevent="handleLinkClick(close)">
      <slot></slot>
    </a>
  </MenuItem>
</template>
