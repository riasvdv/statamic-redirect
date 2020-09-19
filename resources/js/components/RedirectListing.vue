<template>
  <div>
    <data-list
        class="mb-4"
        :columns="columns"
        :rows="rows"
        :sort="true"
        :sort-column="sortColumn"
        :sort-direction="sortDirection"
    >
        <div class="card p-0" slot-scope="{ filteredRows: rows }">
            <data-list-table :rows="rows" @sorted="sorted">
                <template slot="cell-enabled" slot-scope="{ row: collection }">
                    <div v-if="collection.enabled" class="bg-green block h-3 w-2 mx-auto rounded-full"></div>
                    <div v-else class="bg-red block h-3 w-2 mx-auto rounded-full"></div>
                </template>
                <template slot="cell-source" slot-scope="{ row: collection }">
                    <a :href="cp_url('redirect/redirects/' + collection.slug)">{{ collection.source }}</a>
                </template>
                <template slot="actions" slot-scope="{ row: collection, index }">
                    <dropdown-list>
                        <dropdown-item :text="__('Edit')" :redirect="cp_url('redirect/redirects/' + collection.slug)" />
                        <dropdown-item
                            :text="__('Delete')"
                            class="warning"
                            @click="confirmDeleteRow(collection.slug, index)" />
                    </dropdown-list>

                    <confirmation-modal
                        v-if="deletingRow !== false"
                        :title="deletingModalTitle"
                        :bodyText="__('Are you sure you want to delete this redirect?')"
                        :buttonText="__('Delete')"
                        :danger="true"
                        @confirm="deleteRow('/redirect/redirects')"
                        @cancel="cancelDeleteRow"
                    >
                    </confirmation-modal>
                </template>
            </data-list-table>
        </div>
    </data-list>
    <data-list-pagination
        :per-page="this.perPage"
        :resource-meta="{
          last_page: Math.ceil(this.initialRows.length / this.perPage),
          current_page: this.page,
          total: this.initialRows.length,
        }"
        @page-selected="selectPage"
        @per-page-changed="setPerPage"
    ></data-list-pagination>
  </div>
</template>

<script>
    import DeletesListingRow from './DeletesListingRow.js'

    export default {

        mixins: [DeletesListingRow],

        props: [
            'initial-rows',
            'columns',
        ],

        data() {
            return {
                perPage: Statamic.$config.get('paginationSize'),
                page: 1,
                sortColumn: 'source',
                sortDirection: 'asc',
            }
        },

      computed: {
          rows() {
            let rows = _.sortBy(this.initialRows, this.sortColumn);

            if (this.sortDirection === 'desc') {
              rows = rows.reverse();
            }

            return rows.slice((this.page - 1) * this.perPage, this.page * this.perPage)
          }
      },

      methods: {
        selectPage(page) {
          this.page = page;
        },

        setPerPage(perPage) {
          this.perPage = perPage;
        },

        resetPage() {
          this.page = 1;
        },

        sorted(column, direction) {
          this.sortColumn = column;
          this.sortDirection = direction;
        },
      }

    }
</script>
