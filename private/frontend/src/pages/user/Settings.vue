<template>
  <div>
    <PageTitle text="nav.settings" />

    <h1 class="text-2xl font-bold">{{ $t('settings.title') }}</h1>

    <div class="flex items-center gap-2 mt-2">
      <label for="locale">
        <span>{{ $t('settings.locale') }}</span>
      </label>
      <div class="form-control">
        <select class="select select-bordered" id="locale" v-model="locale">
          <option>en</option>
          <option>sk</option>
        </select>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'

const i18n = useI18n()
const locale = ref(i18n.locale.value)

// TODO: use existing loadLocaleMessages and setI18nLanguage from @/i18n.ts
watch(locale, async (newLocale) => {
  const messages = await import(
    /* webpackChunkName: "locale-[request]" */ `../../locales/${newLocale}.js`
  )

  i18n.setLocaleMessage(newLocale, messages.default)
  i18n.locale.value = newLocale

  document.querySelector('html')!.setAttribute('lang', newLocale)
})
</script>
