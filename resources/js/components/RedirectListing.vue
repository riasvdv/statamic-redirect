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
            <template slot="cell-enabled" slot-scope="{ row: redirect }">
              <div
                v-if="redirect.enabled"
                class="bg-green block h-3 w-2 mx-auto rounded-full"
              ></div>
              <div
                v-else
                class="bg-red block h-3 w-2 mx-auto rounded-full"
              ></div>
            </template>
            <template slot="cell-source" slot-scope="{ row: redirect }">
              <a style="word-break: break-all" :href="cp_url('redirect/redirects/' + redirect.id)">{{
                redirect.source
              }}</a>
            </template>
            <template slot="actions" slot-scope="{ row: redirect, index }">
              <dropdown-list>
                <dropdown-item
                  :text="__('Edit')"
                  :redirect="cp_url('redirect/redirects/' + redirect.id)"
                />
                <dropdown-item
                    :text="__('Delete')"
                    class="warning"
                    @click="$refs[`deleter_${redirect.id}`].confirm()"
                >
                  <resource-deleter
                      :ref="`deleter_${redirect.id}`"
                      :resource="redirect"
                      @deleted="removeRow(redirect.id);">
                  </resource-deleter>
                </dropdown-item>
              </dropdown-list>
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
import Listing from "../../../vendor/statamic/cms/resources/js/components/Listing";

export default {
  mixins: [Listing],

  data() {
    return {
      listingKey: "redirects",
      preferencesPrefix: `redirect.redirects`,
      requestUrl: cp_url(`redirect/api/redirects`),
      columns: this.columns,
    };
  },

  methods: {
    removeRow(row) {
      let id = row.id;
      let i = _.indexOf(this.items, _.findWhere(this.items, { id }));
      this.items.splice(i, 1);
      if (this.items.length === 0) location.reload();

      const self = this;
      Object.keys(this.$refs).forEach(function (key) {
        if (key.includes('deleter_') && self.$refs[key] !== undefined) {
          self.$refs[key].cancel();
        }
      })
    },
  }
};
</script>
