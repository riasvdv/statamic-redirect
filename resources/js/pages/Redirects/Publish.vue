<script setup>
import { Head, router } from '@statamic/cms/inertia';
import { Header, Button, PublishContainer, PublishTabs } from '@statamic/cms/ui';
import { SaveButtonOptions } from '@statamic/cms';
import { Pipeline, Request, BeforeSaveHooks, AfterSaveHooks } from '@statamic/cms/save-pipeline';
import { onMounted, onUnmounted, ref, useTemplateRef } from 'vue';

const props = defineProps([
  'isCreating',
  'icon',
  'title',
  'blueprint',
  'values',
  'meta',
  'submitUrl',
  'asConfig',
  'listingUrl',
  'createUrl',
])

const containerName = Statamic.$slug.separatedBy('_').create(props.title);
const container = useTemplateRef('container');
const currentValues = ref(props.values);
const currentMeta = ref(props.meta);
const errors = ref({});
const saving = ref(false);

function afterSaveOption() {
  return Statamic.$preferences.get('redirects.after_save');
}

function save() {
  new Pipeline()
    .provide({ container, errors, saving })
    .through([
      new BeforeSaveHooks('entry'),
      new Request(props.submitUrl, props.isCreating ? 'post' : 'patch'),
      new AfterSaveHooks('entry'),
    ])
    .then((response) => {
      Statamic.$toast.success(__('Saved'));

      const option = afterSaveOption();

      if (option === 'create_another') {
        router.get(props.createUrl);
        return;
      }

      if (option === 'continue_editing') {
        if (props.isCreating) {
          router.get(response.data.data.edit_url);
        }
        return;
      }

      // Default: Go to listing
      router.get(props.listingUrl);
    });
}

let saveKeyBinding;

onMounted(() => {
  saveKeyBinding = Statamic.$keys.bindGlobal(['mod+s'], (e) => {
    e.preventDefault();
    save();
  });
});

onUnmounted(() => saveKeyBinding.destroy());
</script>

<template>
  <Head :title="[title, __('Redirect')]" />

  <Header :title="title" :icon="icon">
    <SaveButtonOptions
      :show-options="true"
      preferences-prefix="redirects"
    >
      <Button variant="primary" :text="__('Save')" @click="save" :disabled="saving" />
    </SaveButtonOptions>
  </Header>

  <PublishContainer
    ref="container"
    :name="containerName"
    :blueprint="blueprint"
    :meta="currentMeta"
    :errors="errors"
    :as-config="asConfig"
    v-model="currentValues"
  >
    <PublishTabs />
  </PublishContainer>
</template>
