<template>
  <div>
    <data-list
        class="mb-4"
        :columns="columns"
        :rows="items"
        :sort="true"
        :sort-column="sortColumn"
        :sort-direction="sortDirection"
    >
        <div class="card p-0" slot-scope="{ filteredRows: rows }">
            <data-list-table :rows="rows" @sorted="sorted">
                <template slot="cell-url" slot-scope="{ row: collection }">
                  <span v-html="collection.url"></span>
                </template>
                <template slot="cell-handled" slot-scope="{ row: collection }">
                    <div v-if="collection.handled" class="bg-green block h-3 w-2 mx-auto rounded-full"></div>
                    <div v-else class="bg-red block h-3 w-2 mx-auto rounded-full"></div>
                </template>
                <template slot="cell-latest" slot-scope="{ row: collection }">
                  <relative-date :date="collection.latest"></relative-date>
                </template>
                <template slot="actions" slot-scope="{ row: collection, index }">
                  <a v-if="!collection.handled" :href="cp_url('redirect/redirects/create') + '?source=/' + encodeURI(collection.url)" class="text-blue px-2 inline-block"><i class="fa fa-plus"></i></a>
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
    import DeletesListingRow from './DeletesListingRow.js';
    import RelativeDate from './RelativeDate';

    export default {

        mixins: [DeletesListingRow],

        props: [
            'initial-rows',
            'columns',
        ],

      components: {
        RelativeDate,
      },

        data() {
            return {
                perPage: 10,
                page: 1,
                sortColumn: 'latest',
                sortDirection: 'desc',
            }
        },

      computed: {
          items() {
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
