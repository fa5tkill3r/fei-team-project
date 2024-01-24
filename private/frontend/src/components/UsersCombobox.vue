<template>
  <Combobox v-model="selectedPerson" multiple>
    <label class="form-control">
      <div class="label">
        <span class="label-text">{{ $t('task.assignees') }}</span>
      </div>
      <div class="relative">
        <ComboboxInput
          @change="query = $event.target.value"
          class="input input-bordered w-full"
        />

        <ComboboxButton
          class="btn btn-ghost btn-square btn-sm absolute right-0 top-0 mr-2 mt-2"
        >
          <ChevronUpDownIcon class="w-5 h-5" />
        </ComboboxButton>
      </div>
    </label>

    <ComboboxOptions class="relative">
      <div class="menu bg-base-200 rounded absolute top-1 w-full">
        <ComboboxOption
          v-for="person in filteredPeople"
          :key="person"
          :value="person"
          as="template"
        >
          <li>
            <a>
              {{ person }}{{ selectedPerson.includes(person) ? ' ✓' : '' }}
            </a>
          </li>
        </ComboboxOption>
      </div>
    </ComboboxOptions>
  </Combobox>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { ChevronUpDownIcon } from '@heroicons/vue/24/solid'
import {
  Combobox,
  ComboboxInput,
  ComboboxOptions,
  ComboboxOption,
  ComboboxButton,
} from '@headlessui/vue'

const people = [
  'Durward Reynolds',
  'Kenton Towne',
  'Therese Wunsch',
  'Benedict Kessler',
  'Katelyn Rohan',
]
const selectedPerson = ref<string[]>([])
const query = ref('')

const filteredPeople = computed(() =>
  query.value === ''
    ? people
    : people.filter((person) => {
        return person.toLowerCase().includes(query.value.toLowerCase())
      }),
)
</script>
