<script setup>
import { StatusIndicator, DropdownItem, Listing } from '@statamic/ui';
import { ref } from 'vue';

const props = defineProps({
    actionUrl: String,
    columns: Array,
    filters: Array,
});
const preferencesPrefix = ref(`redirect.redirects`);
const requestUrl = ref(cp_url(`redirect/api/redirects`));
const items = ref(null);
const page = ref(null);
const perPage = ref(null);

function requestComplete({ items: newItems, parameters }) {
  items.value = newItems;
  page.value = parameters.page;
  perPage.value = parameters.perPage;
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
        <template v-slot:[`cell-source`]="{ row: redirect }">
            <a class="title-index-field" :href="redirect.edit_url" @click.stop>
                <StatusIndicator :status="redirect.enabled ? 'published' : 'hidden'" />
                <span v-text="redirect.source" />
            </a>
        </template>
        <template #prepended-row-actions="{ row: redirect }">
            <DropdownItem
                v-if="redirect.editable"
                :text="__('Edit')"
                :href="redirect.edit_url"
                icon="edit"
            />
        </template>
    </Listing>
</template>
