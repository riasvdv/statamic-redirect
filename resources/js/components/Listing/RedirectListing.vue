<script setup>
import { StatusIndicator, DropdownItem, Listing, Header } from '@statamic/ui';
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    actionUrl: String,
    columns: Array,
    filters: Array,
    reordering: Boolean,
});
const preferencesPrefix = ref(`redirect.redirects`);
const requestUrl = ref(cp_url(`redirect/api/redirects`));
const items = ref(null);
const page = ref(null);
const perPage = ref(null);
const view = ref('list');

function requestComplete({ items: newItems, parameters }) {
  items.value = newItems;
  page.value = parameters.page;
  perPage.value = parameters.perPage;
}

function reordered(value) {
  axios.post(cp_url(`redirect/api/redirects/reorder`), {
    redirects: value,
  }).then(() => {
    Statamic.$toast.success(__('Redirects re-ordered'));
  }).catch(() => {
    Statamic.$toast.error(__('Something went wrong'));
  });
}
</script>

<template>
    <div>
      <Header title="Redirects" icon="arrow-up-right">
        <ui-toggle-group v-model="view">
          <ui-toggle-item icon="navigation" value="tree" />
          <ui-toggle-item icon="layout-list" value="list" />
        </ui-toggle-group>

        <ui-dropdown>
          <ui-dropdown-menu>
            <ui-dropdown-item
                v-if="can('create redirects')"
                icon="upload"
                text="Import CSV"
                :href="cp_url('redirect/redirects/import')"
            ></ui-dropdown-item>
            <ui-dropdown-item
                icon="download"
                text="Export as CSV"
                :href="cp_url('redirect/redirects/export/csv?download=true')"
            ></ui-dropdown-item>
            <ui-dropdown-item
                icon="download"
                text="Export as JSON"
                :href="cp_url('redirect/redirects/export/json?download=true')"
            ></ui-dropdown-item>
          </ui-dropdown-menu>
        </ui-dropdown>
        <ui-button
            v-if="can('create redirects')"
            :href="cp_url('redirect/redirects/create')"
            text="Create Redirect"
            variant="primary"
        />
      </Header>
      <Listing
          ref="listing"
          :url="requestUrl"
          :columns="columns"
          :action-url="actionUrl"
          :preferences-prefix="preferencesPrefix"
          :filters="filters"
          :reorderable="view === 'tree'"
          push-query
          @request-completed="requestComplete"
          @reordered="reordered"
      >
          <template v-slot:[`cell-source`]="{ row: redirect }">
              <a v-if="can('edit redirects')" class="title-index-field" :href="redirect.edit_url" @click.stop>
                  <StatusIndicator :status="redirect.enabled ? 'published' : 'hidden'" />
                  <span v-text="redirect.source" />
              </a>
            <span v-else class="title-index-field">
              <StatusIndicator :status="redirect.enabled ? 'published' : 'hidden'" />
              <span v-text="redirect.source" />
            </span>
          </template>
          <template v-slot:[`cell-last_used_at`]="{ row: redirect }">
            <span v-text="redirect.last_used_at"></span>
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
    </div>
</template>
