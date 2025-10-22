<script setup>
import { Head } from '@statamic/cms/inertia';
import RedirectListing from "../../components/Listing/RedirectListing.vue";

defineProps({
  columns: { type: Array, required: true },
  filters: { type: Array, required: true },
  actionUrl: { type: String, required: true },
})
</script>

<template>
  <Head :title="[__('Redirects'), __('Redirect')]" />

  <RedirectListing
      :columns="columns"
      :filters="filters"
      :action-url="actionUrl"
  />
</template>
