<template>
  <div class="avatar placeholder">
    <div
      class="rounded-full text-gray-800 font-bold"
      :class="avatarClasses"
      :style="styles"
    >
      <img
        v-if="user.avatar"
        src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg"
      />

      <span v-else>{{ initials }}</span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { stringToHslColor } from '@/lib/utils'
import { User } from '@/types'
import { computed } from 'vue'

interface UserAvatarProps {
  user: User
  size?: 'sm' | 'md'
}

const { user, size } = withDefaults(defineProps<UserAvatarProps>(), {
  size: 'sm',
})

const classMap = {
  sm: 'w-8 text-sm',
  md: 'w-10',
}

const avatarClasses = computed(() => classMap[size] ?? classMap.sm)
const styles = computed(() => ({
  background: stringToHslColor(user.email),
}))
const initials = computed(() => {
  return (user.first_name[0] + user.last_name[0]).toUpperCase()
})
</script>
