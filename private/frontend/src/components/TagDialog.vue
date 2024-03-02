<template>
  <Modal v-model="model">
    <div class="modal-box">
      <h1 class="text-xl font-bold mb-3">
        {{ editing ? $t('tags.edit_title') : $t('tags.add_title') }}
      </h1>

      <TagLabel :name="name || $t('tags.preview')" :color="color" />

      <div class="form-control">
        <label class="label">
          <span class="label-text">
            {{ $t('tags.name') }}
          </span>
        </label>
        <input type="text" class="input input-bordered" v-model="name" />
      </div>

      <div class="form-control">
        <label class="label">
          <span class="label-text">
            {{ $t('tags.color') }}
          </span>
        </label>
        <div class="flex gap-2">
          <button
            class="btn btn-square"
            @click="randomize"
            :style="buttonStyles"
          >
            <ArrowPathIcon class="w-6 h-6" />
          </button>
          <input
            type="text"
            class="input input-bordered w-full"
            :class="{ 'input-error': invalidColor }"
            v-model="hexColor"
            @input="updateHsl"
          />
        </div>
      </div>

      <div class="flex justify-end gap-2 mt-4">
        <button class="btn btn-error" @click="model = false">
          {{ $t('cancel') }}
        </button>
        <button class="btn btn-primary w-20" @click="save">
          <span v-if="loading" class="loading loading-spinner"></span>
          <span v-else>{{ editing ? $t('update') : $t('add') }}</span>
        </button>
      </div>
    </div>
  </Modal>
</template>

<script setup lang="ts">
import { hexToRgb, hslToRgb, rgbToHex, rgbToHsl } from '@/lib/utils'
import { useAuthStore } from '@/stores/auth'
import { Tag } from '@/types'
import { ArrowPathIcon } from '@heroicons/vue/24/outline'
import { computed, defineModel, ref, watch } from 'vue'
import Modal from './ui/Modal.vue'
import TagLabel from './ui/Tag.vue'

const auth = useAuthStore()
const props = defineProps<{ edit?: Tag | null }>()
const model = defineModel<boolean>()
const emit = defineEmits<{ update: [Tag] }>()
const color = ref(getRandomColor())
const name = ref('')
const loading = ref(false)
const hexColor = ref(
  rgbToHex(hslToRgb(color.value[0], color.value[1], color.value[2])),
)
const invalidColor = ref(false)

const editing = computed(() => !!props.edit)
const buttonStyles = computed(() => {
  const [h, s, l] = color.value
  const hsl = `${h}, ${s}%, ${l}%`

  return {
    borderColor: `hsla(${hsl}, 0.3)`,
    color: `hsla(${hsl}, 1)`,
    backgroundColor: `hsla(${hsl}, 0.18)`,
  }
})

watch(model, (value) => {
  if (value && !props.edit) {
    name.value = ''
    randomize()
  }
})

watch(
  () => props.edit,
  (value) => {
    console.log(value)

    if (value) {
      name.value = value.name
      hexColor.value = value.color
      const [r, g, b] = hexToRgb(value.color)
      color.value = rgbToHsl(r, g, b)
      model.value = true
    }
  },
)

function updateHsl(e: Event) {
  if (!e.target) {
    return
  }

  const value = ((e.target as HTMLInputElement).value as string).trim()

  if (!value) {
    invalidColor.value = true
    return
  }
  if (!/^#[0-9A-F]{6}$/i.test(value)) {
    invalidColor.value = true
    return
  }

  const [r, g, b] = hexToRgb(value)

  invalidColor.value = false
  color.value = rgbToHsl(r, g, b)
}

function getRandomColor() {
  const h = Math.random() * 360
  const s = 50
  const l = Math.random() * (80 - 50) + 50

  return [h, s, l]
}

function randomize() {
  color.value = getRandomColor()
  hexColor.value = rgbToHex(
    hslToRgb(color.value[0], color.value[1], color.value[2]),
  )
}

function save() {
  if (!name.value || invalidColor.value || loading.value) {
    return
  }

  if (editing.value) {
    updateTag()
  } else {
    addTag()
  }
}

function addTag() {
  loading.value = true
  auth.client
    .post({ name: name.value, color: hexColor.value }, 'tags')
    .json()
    .then(({ data }: any) => {
      emit('update', data as Tag)
    })
    .finally(() => {
      loading.value = false
      model.value = false
    })
}

function updateTag() {
  if (!props.edit) {
    return
  }

  loading.value = true
  auth.client
    .put({ name: name.value, color: hexColor.value }, `tags/${props.edit.id}`)
    .json()
    .then(({ data }: any) => {
      emit('update', data as Tag)
    })
    .finally(() => {
      loading.value = false
      model.value = false
    })
}
</script>
