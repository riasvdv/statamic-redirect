<template>
  <div>
    <div v-if="initializing" class="card loading">
      <loading-graphic />
    </div>

    <data-list
      v-if="!initializing"
      ref="datalist"
      :rows="items"
      :columns="columns"
      :sort="false"
      :sort-column="sortColumn"
      :sort-direction="sortDirection"
      @visible-columns-updated="visibleColumns = $event"
    >
      <div slot-scope="{ hasSelections }">
        <div class="card overflow-hidden p-0 relative">
          <div class="flex flex-wrap items-center justify-between px-2 pb-2 text-sm border-b">
            <data-list-filter-presets
              ref="presets"
              :active-preset="activePreset"
              :active-preset-payload="activePresetPayload"
              :active-filters="activeFilters"
              :has-active-filters="hasActiveFilters"
              :preferences-prefix="preferencesPrefix"
              :search-query="searchQuery"
              @selected="selectPreset"
              @reset="filtersReset"
            />

            <div class="w-full flex-1">
              <data-list-search class="h-8 mt-2 min-w-[240px] w-full" ref="search" v-model="searchQuery" :placeholder="searchPlaceholder" />
            </div>

            <div class="flex space-x-2 mt-2">
              <button class="btn btn-sm ml-2" v-text="__('Reset')" v-show="isDirty" @click="$refs.presets.refreshPreset()" />
              <button class="btn btn-sm ml-2" v-text="__('Save')" v-show="isDirty" @click="$refs.presets.savePreset()" />
              <data-list-column-picker :preferences-key="preferencesKey('columns')" />
            </div>

            <a :href="cp_url('redirect/errors/clear')" class="ml-2 mt-2 btn btn-sm flex items-center">Clear all errors</a>
          </div>
          <div>
            <data-list-filters
                ref="filters"
                :filters="filters"
                :active-preset="activePreset"
                :active-preset-payload="activePresetPayload"
                :active-filters="activeFilters"
                :active-filter-badges="activeFilterBadges"
                :active-count="activeFilterCount"
                :search-query="searchQuery"
                :is-searching="true"
                :saves-presets="true"
                :preferences-prefix="preferencesPrefix"
                @changed="filterChanged"
                @saved="$refs.presets.setPreset($event)"
                @deleted="$refs.presets.refreshPresets()"
            />
          </div>

          <div v-show="items.length === 0" class="p-6 text-center text-gray-500" v-text="__('No results')" />

          <div class="overflow-x-auto overflow-y-hidden">
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
                <a class="text-blue hover:text-blue-dark" :href="cp_url('redirect/errors/' + error.id)" style="word-break: break-all">{{ error.url }}</a>
              </template>
              <template slot="cell-lastSeenAt" slot-scope="{ row: error }">
                <span v-html="relativeDate(error.lastSeenAt)"></span>
              </template>
              <template slot="cell-handled" slot-scope="{ row: error }">
                <div class="flex items-center">
                  <div
                    v-if="error.handled"
                    class="little-dot mr-2 bg-green-600"
                  ></div>
                  <div
                    v-else
                    class="little-dot mr-2 bg-red-500"
                  ></div>
                </div>
              </template>
              <template slot="cell-handledDestination" slot-scope="{ row: error }">
                <span style="word-break: break-all">{{ error.handledDestination }}</span>
              </template>
              <template slot="actions" slot-scope="{ row: error, index }">
                <a
                  v-if="!error.handled"
                  :href="
                    cp_url('redirect/redirects/create') +
                    '?source=' +
                    encodeURI(error.url)
                  "
                  class="text-blue flex align-center"
                >
                  <svg
                    class="w-4 h-4"
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
      return moment.unix(time).fromNow();
    },
  },
};
</script>
