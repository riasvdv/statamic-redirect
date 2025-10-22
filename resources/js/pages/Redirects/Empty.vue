<script setup>
import { Head } from '@statamic/cms/inertia';
import { Icon, EmptyStateMenu, EmptyStateItem, DocsCallout } from '@statamic/cms/ui';

defineProps({
  createUrl: { type: String, required: true },
})
</script>

<template>
  <Head :title="[__('Redirects'), __('Redirect')]" />

  <header class="py-8 mt-8 text-center">
    <h1 class="text-[25px] font-medium antialiased flex justify-center items-center gap-3">
      <Icon name="arrow-up-right" class="size-5 text-gray-500"></Icon>
      {{ __('Redirects') }}
    </h1>
  </header>

  <EmptyStateMenu :heading="__('A redirect on a website is a way to automatically send visitors from one URL to another. It\'s commonly used when a page has been moved, deleted, or replaced, ensuring users and search engines reach the correct location.')">
    <EmptyStateItem
        :href="createUrl"
        icon="arrow-up-right"
        :heading="__('Create Redirect')"
        :description="__('Get started by creating your first redirect.')"
    ></EmptyStateItem>
  </EmptyStateMenu>

  <DocsCallout
      topic="Statamic Redirect"
      url="https://statamic.com/addons/rias/redirect"
  />
</template>
