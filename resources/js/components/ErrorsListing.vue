<template>
  <div>
    <div v-if="initializing" class="card loading">
      <loading-graphic />
    </div>

    <data-list
      v-if="!initializing"
      class="mb-4"
      :visible-columns="columns"
      :columns="columns"
      :rows="items"
      :sort="false"
      :sort-column="sortColumn"
      :sort-direction="sortDirection"
    >
      <div slot-scope="{ hasSelections }">
        <div class="card p-0 relative">
          <data-list-filter-presets
            ref="presets"
            :active-preset="activePreset"
            :preferences-prefix="preferencesPrefix"
            @selected="selectPreset"
            @reset="filtersReset"
          />
          <div class="data-list-header">
            <data-list-filters
              :filters="filters"
              :active-preset="activePreset"
              :active-preset-payload="activePresetPayload"
              :active-filters="activeFilters"
              :active-filter-badges="activeFilterBadges"
              :active-count="activeFilterCount"
              :search-query="searchQuery"
              :saves-presets="true"
              :preferences-prefix="preferencesPrefix"
              @filter-changed="filterChanged"
              @search-changed="searchChanged"
              @saved="$refs.presets.setPreset($event)"
              @deleted="$refs.presets.refreshPresets()"
              @restore-preset="$refs.presets.viewPreset($event)"
              @reset="filtersReset"
            />
          </div>

          <div
            v-show="items.length === 0"
            class="p-3 text-center text-grey-50"
            v-text="__('No results')"
          />

          <data-list-table
            v-show="items.length"
            :allow-bulk-actions="false"
            :loading="loading"
            :reorderable="false"
            :sortable="true"
            :toggle-selection-on-row-click="false"
            :allow-column-picker="true"
            :column-preferences-key="preferencesKey('columns')"
            @sorted="sorted"
          >
            <template slot="cell-url" slot-scope="{ row: error }">
              <span style="word-break: break-all" :title="
                'User agent: ' + (error.hits[error.hits.length - 1].data.userAgent || 'n/a') + '\n' +
                'IP: ' + (error.hits[error.hits.length - 1].data.ip || 'n/a') + '\n' +
                'Referer: ' + (error.hits[error.hits.length - 1].data.referer || 'n/a') + '\n'
              ">{{ error.url }}</span>
            </template>
            <template slot="cell-hits" slot-scope="{ row: error }">
              {{ error.hits.length }}
            </template>
            <template slot="cell-latest" slot-scope="{ row: error }">
              <span v-html="relativeDate(error.latest)"></span>
            </template>
            <template slot="cell-handled" slot-scope="{ row: error }">
              <div
                v-if="error.handled"
                class="bg-green block h-3 w-2 mr-auto rounded-full"
              ></div>
              <div
                v-else
                class="bg-red block h-3 w-2 mr-auto rounded-full"
              ></div>
            </template>
            <template slot="actions" slot-scope="{ row: error, index }">
              <a
                v-if="!error.handled"
                :href="
                  cp_url('redirect/redirects/create') +
                  '?source=' +
                  encodeURI(error.url)
                "
                class="text-blue inline-block"
              >
                <svg
                  class="w-4 h-4 mr-2"
                  aria-hidden="true"
                  focusable="false"
                  data-prefix="far"
                  data-icon="plus"
                  role="img"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 384 512"
                >
                  <path
                    fill="currentColor"
                    d="M368 224H224V80c0-8.84-7.16-16-16-16h-32c-8.84 0-16 7.16-16 16v144H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h144v144c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16V288h144c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z"
                  ></path>
                </svg>
              </a>
            </template>
          </data-list-table>
        </div>
        <data-list-pagination
          class="mt-3"
          :resource-meta="meta"
          :per-page="perPage"
          @page-selected="selectPage"
          @per-page-changed="changePerPage"
        />
      </div>
    </data-list>
  </div>
</template>

<script>
import DeletesListingRow from "./DeletesListingRow.js";
import Listing from "../../../vendor/statamic/cms/resources/js/components/Listing";

export default {
  mixins: [Listing, DeletesListingRow],

  components: {
    Listing,
  },

  data() {
    return {
      listingKey: "errors",
      preferencesPrefix: `redirect.errors`,
      requestUrl: cp_url(`redirect/api/errors`),
      columns: this.columns,
    };
  },

  methods: {
    relativeDate(time) {
      return moment(time * 1000).fromNow();
    },
  },
};
</script>
