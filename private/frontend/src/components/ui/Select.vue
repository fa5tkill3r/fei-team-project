<script setup lang="ts">
import {
  Listbox,
  ListboxButton,
  ListboxOption,
  ListboxOptions,
} from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/24/solid'
import { watch } from 'vue'

const selectedItem = defineModel<string>()
const emit = defineEmits<{ change: [string] }>()

watch(selectedItem, (value) => {
  emit('change', value!)
})

defineProps<{
  list: string[]
}>()
</script>

<template>
  <Listbox v-model="selectedItem">
    <div class="relative mt-1">
      <ListboxButton
        class="relative w-full cursor-default rounded-lg bg-base-100 border border-base-content/20 py-2 pl-3 pr-10 text-left focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-offset-2 sm:text-sm min-h-6 text-base-content"
      >
        <span class="block truncate">{{ selectedItem }}</span>
        <span
          class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
        >
          <ChevronUpDownIcon
            class="h-5 w-5 text-base-content"
            aria-hidden="true"
          />
        </span>
      </ListboxButton>

      <transition
        leave-active-class="transition duration-100 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <ListboxOptions
          class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-base-200 py-1 shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm z-10"
        >
          <ListboxOption
            v-slot="{ active, selected }"
            v-for="item in list"
            :key="item"
            :value="item"
            as="template"
          >
            <li
              :class="[
                active
                  ? 'bg-primary text-primary-content'
                  : 'text-base-content',
                'relative cursor-default select-none py-2 pl-10 pr-4',
              ]"
            >
              <span
                :class="[
                  selected ? 'font-medium' : 'font-normal',
                  'block truncate',
                ]"
              >
                {{ item }}
              </span>
              <span
                v-if="selected"
                class="absolute inset-y-0 left-0 flex items-center pl-3"
                :class="active ? 'text-primary-content' : 'text-base-content'"
              >
                <CheckIcon class="h-5 w-5" aria-hidden="true" />
              </span>
            </li>
          </ListboxOption>
        </ListboxOptions>
      </transition>
    </div>
  </Listbox>
</template>
