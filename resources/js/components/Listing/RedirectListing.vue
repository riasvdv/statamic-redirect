<template>
  <div>
    <header class="mb-6">

      <div class="flex items-center">
        <h1 class="flex-1" >Redirects</h1>

        <dropdown-list class="mr-2" v-if="!!this.$scopedSlots.twirldown">
          <slot name="twirldown" />
        </dropdown-list>

        <a
            v-if="canCreate"
            class="btn-primary"
            :href="createUrl"
            v-text="createLabel"></a>
      </div>
    </header>

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
              <button class="btn btn-sm ml-2" v-text="__('Reset')" @click="$refs.presets.refreshPreset()" />
              <button class="btn btn-sm ml-2" v-text="__('Save')" @click="$refs.presets.savePreset()" />
              <data-list-column-picker :preferences-key="preferencesKey('columns')" />
            </div>
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

          <div
            v-show="items.length === 0"
            class="p-3 text-center text-grey-50"
            v-text="__('No results')"
          />

          <data-list-table
            v-show="items.length"
            :loading="loading"
            :reorderable="true"
            :sortable="false"
            :toggle-selection-on-row-click="false"
            :allow-column-picker="true"
            :column-preferences-key="preferencesKey('columns')"
            @sorted="sorted"
            @reordered="reordered"
          >
            <template slot="cell-source" slot-scope="{ row: redirect }">
              <a style="word-break: break-all" :href="cp_url('redirect/redirects/' + redirect.id)">{{
                redirect.source
              }}</a>
            </template>
            <template slot="cell-match_type" slot-scope="{ row: redirect }">
              <span v-text="redirect.match_type"></span>
            </template>
            <template slot="cell-type" slot-scope="{ row: redirect }">
              <span v-text="redirect.type"></span>
            </template>
            <template slot="cell-enabled" slot-scope="{ row: redirect }">
              <div v-if="redirect.enabled" class="status-index-field select-none status-published">Enabled</div>
              <div v-else class="status-index-field select-none status-draft bg-red-100">Disabled</div>
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
                      :reload="true"
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
import Listing from "../../../../vendor/statamic/cms/resources/js/components/Listing";

export default {
  mixins: [Listing],

  props: {
    canCreate: { type: Boolean, required: true },
    createUrl: { type: String, required: true },
    createLabel: { type: String, required: true },
    searchQuery: { type: String, required: false, default: '' },
  },

  data() {
    return {
      listingKey: "redirects",
      preferencesPrefix: `redirect.redirects`,
      requestUrl: cp_url(`redirect/api/redirects`),
      currentSite: this.site,
      initialSite: this.site,
      columns: this.columns,
    };
  },

  watch: {
    activeFilters: {
      deep: true,
      handler(filters) {
        this.currentSite = filters.site ? filters.site.site : null;
      }
    },

    site(site) {
      this.currentSite = site;
    },

    currentSite(site) {
      this.setSiteFilter(site);
      this.$emit('site-changed', site);
    }
  },

  methods: {
    setSiteFilter(site) {
      this.filterChanged({ handle: 'site', values: { site }});
    },

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

    reordered(event) {
      this.$axios.post(cp_url(`redirect/api/redirects/reorder`), {
        redirects: event,
      }).then(() => {
        this.$toast.success(__('Redirects re-ordered'));
      }).catch(() => {
        this.$toast.error(__('Something went wrong'));
      });
    }
  }
};
</script>
