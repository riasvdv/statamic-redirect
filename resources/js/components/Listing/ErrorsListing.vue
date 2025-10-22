<script setup>
import { StatusIndicator, DropdownItem, Listing } from '@statamic/cms/ui';
import { ref } from 'vue';

const props = defineProps({
  actionUrl: String,
  columns: Array,
  filters: Array,
});
const preferencesPrefix = ref(`redirect.errors`);
const requestUrl = ref(cp_url(`redirect/api/errors`));
const items = ref(null);
const page = ref(null);
const perPage = ref(null);

function requestComplete({ items: newItems, parameters }) {
  items.value = newItems;
  page.value = parameters.page;
  perPage.value = parameters.perPage;
}

function relativeDate(unixTimestamp) {
  const now = Date.now();
  const diff = unixTimestamp * 1000 - now;

  const rtf = new Intl.RelativeTimeFormat('en', { numeric: 'auto' });

  const units = [
    { unit: 'year', ms: 1000 * 60 * 60 * 24 * 365 },
    { unit: 'month', ms: 1000 * 60 * 60 * 24 * 30 },
    { unit: 'day', ms: 1000 * 60 * 60 * 24 },
    { unit: 'hour', ms: 1000 * 60 * 60 },
    { unit: 'minute', ms: 1000 * 60 },
    { unit: 'second', ms: 1000 },
  ];

  for (const { unit, ms } of units) {
    const delta = diff / ms;
    if (Math.abs(delta) >= 1) {
      return rtf.format(Math.round(delta), unit);
    }
  }

  return 'just now';
}
</script>

<template>
  <Listing
      ref="listing"
      :url="requestUrl"
      :columns="columns"
      :action-url="actionUrl"
      :preferences-prefix="preferencesPrefix"
      :filters="filters"
      push-query
      @request-completed="requestComplete"
  >
    <template v-slot:[`cell-url`]="{ row: error }">
      <a class="title-index-field" :href="cp_url('redirect/errors/' + error.id)" style="word-break: break-all" @click.stop>
        <span class="text-xs" v-text="error.url" />
      </a>
    </template>
    <template v-slot:[`cell-lastSeenAt`]="{ row: error }">
      <span v-html="relativeDate(error.lastSeenAt)"></span>
    </template>
    <template v-slot:[`cell-handled`]="{ row: error }">
      <StatusIndicator :status="error.handled ? 'published' : 'unpublished'" />
    </template>
    <template v-slot:[`cell-handledDestination`]="{ row: error }">
      <span style="word-break: break-all">{{ error.handledDestination }}</span>
    </template>

    <template #prepended-row-actions="{ row: error }">
      <DropdownItem
          :text="__('View')"
          :href="cp_url('redirect/errors/') + error.id"
          icon="eye"
      />
      <DropdownItem
          v-if="error.canCreateRedirect"
          :text="__('Create Redirect')"
          :href="
            cp_url('redirect/redirects/create') +
            '?source=' +
            encodeURI(error.url)
          "
          icon="plus"
      />
    </template>
  </Listing>
</template>
