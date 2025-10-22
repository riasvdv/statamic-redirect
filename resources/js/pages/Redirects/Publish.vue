<script setup>
import { Head } from '@statamic/cms/inertia';
import { PublishForm } from '@statamic/cms/ui';

defineProps([
  'isCreating',
  'icon',
  'title',
  'blueprint',
  'values',
  'meta',
  'submitUrl',
  'asConfig',
])

function saved(response) {
  router.get(response.data.data.edit_url + '?created=true');
}
</script>

<template>
  <Head :title="[title, __('Redirect')]" />

  <PublishForm
      :is-creating="true"
      publish-container="base"
      submit-method="POST"

      :submit-url="submitUrl"
      :icon="icon"
      :title="title"
      :blueprint="blueprint"
      :initial-values="values"
      :initial-meta="meta"
      :asConfig="asConfig"
      @saved="saved"
  />
</template>
