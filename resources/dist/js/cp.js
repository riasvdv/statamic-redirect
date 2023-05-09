/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ErrorsListing.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ErrorsListing.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _DeletesListingRow_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./DeletesListingRow.js */ "./resources/js/components/DeletesListingRow.js");
/* harmony import */ var _vendor_statamic_cms_resources_js_components_Listing__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../vendor/statamic/cms/resources/js/components/Listing */ "./vendor/statamic/cms/resources/js/components/Listing.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [_vendor_statamic_cms_resources_js_components_Listing__WEBPACK_IMPORTED_MODULE_1__["default"], _DeletesListingRow_js__WEBPACK_IMPORTED_MODULE_0__["default"]],
  components: {
    Listing: _vendor_statamic_cms_resources_js_components_Listing__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  data: function data() {
    return {
      listingKey: "errors",
      preferencesPrefix: "redirect.errors",
      requestUrl: cp_url("redirect/api/errors"),
      columns: this.columns
    };
  },
  methods: {
    relativeDate: function relativeDate(time) {
      return moment.unix(time).fromNow();
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/PublishFormRedirect.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/PublishFormRedirect.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    title: String,
    action: String,
    blueprint: Array,
    meta: Array,
    redirectTo: String,
    values: Array
  },
  methods: {
    saved: function saved(event) {
      var _this = this;

      setTimeout(function () {
        return _this.redirect(event.data);
      }, 600);
    },
    redirect: function redirect(slug) {
      window.location.href = this.createEditRoute(slug);
    },
    createEditRoute: function createEditRoute(slug) {
      return this.redirectTo.replace("x-redirect", slug);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RedirectListing.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RedirectListing.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _vendor_statamic_cms_resources_js_components_Listing__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../vendor/statamic/cms/resources/js/components/Listing */ "./vendor/statamic/cms/resources/js/components/Listing.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [_vendor_statamic_cms_resources_js_components_Listing__WEBPACK_IMPORTED_MODULE_0__["default"]],
  data: function data() {
    return {
      listingKey: "redirects",
      preferencesPrefix: "redirect.redirects",
      requestUrl: cp_url("redirect/api/redirects"),
      currentSite: this.site,
      initialSite: this.site,
      columns: this.columns
    };
  },
  watch: {
    activeFilters: {
      deep: true,
      handler: function handler(filters) {
        this.currentSite = filters.site ? filters.site.site : null;
      }
    },
    site: function site(_site) {
      this.currentSite = _site;
    },
    currentSite: function currentSite(site) {
      this.setSiteFilter(site);
      this.$emit('site-changed', site);
    }
  },
  methods: {
    setSiteFilter: function setSiteFilter(site) {
      this.filterChanged({
        handle: 'site',
        values: {
          site: site
        }
      });
    },
    removeRow: function removeRow(row) {
      var id = row.id;

      var i = _.indexOf(this.items, _.findWhere(this.items, {
        id: id
      }));

      this.items.splice(i, 1);
      if (this.items.length === 0) location.reload();
      var self = this;
      Object.keys(this.$refs).forEach(function (key) {
        if (key.includes('deleter_') && self.$refs[key] !== undefined) {
          self.$refs[key].cancel();
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./vendor/statamic/cms/resources/js/components/Listing.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./vendor/statamic/cms/resources/js/components/Listing.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _data_list_HasActions__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./data-list/HasActions */ "./vendor/statamic/cms/resources/js/components/data-list/HasActions.js");
/* harmony import */ var _data_list_HasFilters__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./data-list/HasFilters */ "./vendor/statamic/cms/resources/js/components/data-list/HasFilters.js");
/* harmony import */ var _data_list_HasPagination__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./data-list/HasPagination */ "./vendor/statamic/cms/resources/js/components/data-list/HasPagination.js");
/* harmony import */ var _data_list_HasPreferences__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./data-list/HasPreferences */ "./vendor/statamic/cms/resources/js/components/data-list/HasPreferences.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }





/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [_data_list_HasActions__WEBPACK_IMPORTED_MODULE_0__["default"], _data_list_HasFilters__WEBPACK_IMPORTED_MODULE_1__["default"], _data_list_HasPagination__WEBPACK_IMPORTED_MODULE_2__["default"], _data_list_HasPreferences__WEBPACK_IMPORTED_MODULE_3__["default"]],
  props: {
    initialSortColumn: String,
    initialSortDirection: String,
    initialColumns: {
      type: Array,
      "default": function _default() {
        return [];
      }
    },
    filters: Array,
    actionUrl: String
  },
  data: function data() {
    return {
      source: null,
      initializing: true,
      loading: true,
      items: [],
      columns: this.initialColumns,
      visibleColumns: this.initialColumns.filter(function (column) {
        return column.visible;
      }),
      sortColumn: this.initialSortColumn,
      sortDirection: this.initialSortDirection,
      meta: null
    };
  },
  computed: {
    parameters: function parameters() {
      return Object.assign({
        sort: this.sortColumn,
        order: this.sortDirection,
        page: this.page,
        perPage: this.perPage,
        search: this.searchQuery,
        filters: this.activeFilterParameters,
        columns: this.visibleColumns.map(function (column) {
          return column.field;
        }).join(',')
      }, this.additionalParameters);
    },
    activeFilterParameters: function activeFilterParameters() {
      return utf8btoa(JSON.stringify(this.activeFilters));
    },
    additionalParameters: function additionalParameters() {
      return {};
    },
    shouldRequestFirstPage: function shouldRequestFirstPage() {
      if (this.page > 1 && this.items.length === 0) {
        this.page = 1;
        return true;
      }

      return false;
    }
  },
  created: function created() {
    this.autoApplyFilters(this.filters);
    this.request();
  },
  watch: {
    parameters: {
      deep: true,
      handler: function handler(after, before) {
        // A change to the search query would trigger both watchers.
        // We only want the searchQuery one to kick in.
        if (before.search !== after.search) return;
        if (JSON.stringify(before) === JSON.stringify(after)) return;
        this.request();
      }
    },
    loading: {
      immediate: true,
      handler: function handler(loading) {
        this.$progress.loading(this.listingKey, loading);
      }
    },
    searchQuery: function searchQuery(query) {
      this.sortColumn = null;
      this.sortDirection = null;
      this.resetPage();
      this.request();
    }
  },
  methods: {
    request: function request() {
      var _this = this;

      if (!this.requestUrl) {
        this.loading = false;
        return;
      }

      this.loading = true;
      if (this.source) this.source.cancel();
      this.source = this.$axios.CancelToken.source();
      this.$axios.get(this.requestUrl, {
        params: this.parameters,
        cancelToken: this.source.token
      }).then(function (response) {
        _this.columns = response.data.meta.columns;
        _this.activeFilterBadges = _objectSpread({}, response.data.meta.activeFilterBadges);
        _this.items = Object.values(response.data.data);
        _this.meta = response.data.meta;
        if (_this.shouldRequestFirstPage) return _this.request();
        _this.loading = false;
        _this.initializing = false;

        _this.afterRequestCompleted();
      })["catch"](function (e) {
        if (_this.$axios.isCancel(e)) return;
        _this.loading = false;
        _this.initializing = false;

        _this.$toast.error(e.response ? e.response.data.message : __('Something went wrong'), {
          duration: null
        });
      });
    },
    afterRequestCompleted: function afterRequestCompleted(response) {//
    },
    sorted: function sorted(column, direction) {
      this.sortColumn = column;
      this.sortDirection = direction;
    },
    removeRow: function removeRow(row) {
      var id = row.id;

      var i = _.indexOf(this.rows, _.findWhere(this.rows, {
        id: id
      }));

      this.rows.splice(i, 1);
      if (this.rows.length === 0) location.reload();
    }
  }
});

/***/ }),

/***/ "./resources/js/components/DeletesListingRow.js":
/*!******************************************************!*\
  !*** ./resources/js/components/DeletesListingRow.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      deletingRow: false
    };
  },
  computed: {
    deletingModalTitle: function deletingModalTitle() {
      return this.deletingModalTitleFromRowKey("source");
    }
  },
  methods: {
    confirmDeleteRow: function confirmDeleteRow(id, index) {
      this.deletingRow = {
        id: id,
        index: index
      };
    },
    deletingModalTitleFromRowKey: function deletingModalTitleFromRowKey(key) {
      return __("Delete") + " " + this.items[this.deletingRow.index][key];
    },
    deleteRow: function deleteRow(resourceRoute, message) {
      var _this = this;

      var id = this.deletingRow.id;
      message = message || __("Deleted");
      this.$axios["delete"](cp_url("".concat(resourceRoute, "/").concat(id))).then(function () {
        var i = _.indexOf(_this.items, _.findWhere(_this.items, {
          id: id
        }));

        _this.items.splice(i, 1);

        _this.deletingRow = false;

        _this.$toast.success(message);

        location.reload();
      })["catch"](function (e) {
        _this.$toast.error(e.response ? e.response.data.message : __("Something went wrong"));
      });
    },
    cancelDeleteRow: function cancelDeleteRow() {
      this.deletingRow = false;
    }
  }
});

/***/ }),

/***/ "./vendor/statamic/cms/resources/js/components/data-list/HasActions.js":
/*!*****************************************************************************!*\
  !*** ./vendor/statamic/cms/resources/js/components/data-list/HasActions.js ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  methods: {
    actionStarted: function actionStarted() {
      this.loading = true;
    },
    actionCompleted: function actionCompleted() {
      var successful = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
      var response = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      this.loading = false;
      if (successful === false) return;
      this.$events.$emit('clear-selections');
      this.$events.$emit('reset-action-modals');

      if (response.message !== false) {
        this.$toast.success(response.message || __("Action completed"));
      }

      this.afterActionSuccessfullyCompleted();
    },
    afterActionSuccessfullyCompleted: function afterActionSuccessfullyCompleted() {
      this.request();
    }
  }
});

/***/ }),

/***/ "./vendor/statamic/cms/resources/js/components/data-list/HasFilters.js":
/*!*****************************************************************************!*\
  !*** ./vendor/statamic/cms/resources/js/components/data-list/HasFilters.js ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      activePreset: null,
      activePresetPayload: {},
      searchQuery: '',
      activeFilters: {},
      activeFilterBadges: {}
    };
  },
  computed: {
    activeFilterCount: function activeFilterCount() {
      var count = Object.keys(this.activeFilters).length;

      if (this.activeFilters.hasOwnProperty('fields')) {
        count = count + Object.keys(this.activeFilters.fields).filter(function (field) {
          return field != 'badge';
        }).length - 1;
      }

      return count;
    },
    hasActiveFilters: function hasActiveFilters() {
      return this.activeFilterCount > 0;
    }
  },
  methods: {
    searchChanged: function searchChanged(query) {
      this.searchQuery = query;
    },
    hasFields: function hasFields(values) {
      for (var fieldHandle in values) {
        if (values[fieldHandle]) return true;
      }

      return false;
    },
    filterChanged: function filterChanged(_ref) {
      var handle = _ref.handle,
          values = _ref.values;
      var unselectAll = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;

      if (values && this.hasFields(values)) {
        Vue.set(this.activeFilters, handle, values);
      } else {
        Vue["delete"](this.activeFilters, handle);
      }

      if (unselectAll) this.unselectAllItems();
    },
    filtersChanged: function filtersChanged(filters) {
      this.activeFilters = {};

      for (var handle in filters) {
        var values = filters[handle];
        this.filterChanged({
          handle: handle,
          values: values
        }, false);
      }

      this.unselectAllItems();
    },
    filtersReset: function filtersReset() {
      this.activePreset = null;
      this.activePresetPayload = {};
      this.searchQuery = '';
      this.activeFilters = {};
      this.activeFilterBadges = {};
    },
    unselectAllItems: function unselectAllItems() {
      if (this.$refs.dataList) {
        this.$refs.dataList.clearSelections();
      }
    },
    selectPreset: function selectPreset(handle, preset) {
      this.activePreset = handle;
      this.activePresetPayload = preset;
      this.searchQuery = preset.query;
      this.filtersChanged(preset.filters);
    },
    autoApplyFilters: function autoApplyFilters(filters) {
      if (!filters) return;
      var values = {};
      filters.filter(function (filter) {
        return !_.isEmpty(filter.auto_apply);
      }).forEach(function (filter) {
        values[filter.handle] = filter.auto_apply;
      });
      this.activeFilters = values;
    }
  }
});

/***/ }),

/***/ "./vendor/statamic/cms/resources/js/components/data-list/HasPagination.js":
/*!********************************************************************************!*\
  !*** ./vendor/statamic/cms/resources/js/components/data-list/HasPagination.js ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: {
    initialPerPage: {
      type: Number,
      "default": function _default() {
        return Statamic.$config.get('paginationSize');
      }
    }
  },
  data: function data() {
    return {
      perPage: this.initialPerPage,
      page: 1
    };
  },
  mounted: function mounted() {
    this.setInitialPerPage();
  },
  methods: {
    setInitialPerPage: function setInitialPerPage() {
      if (!this.hasPreferences) {
        return;
      }

      this.perPage = this.getPreference('per_page') || this.initialPerPage;
    },
    changePerPage: function changePerPage(perPage) {
      var _this = this;

      perPage = parseInt(perPage);
      var promise = this.hasPreferences ? this.setPreference('per_page', perPage != this.initialPerPage ? perPage : null) : Promise.resolve();
      promise.then(function (response) {
        _this.perPage = perPage;

        _this.resetPage();
      });
    },
    selectPage: function selectPage(page) {
      this.page = page;
    },
    resetPage: function resetPage() {
      this.page = 1;
    }
  }
});

/***/ }),

/***/ "./vendor/statamic/cms/resources/js/components/data-list/HasPreferences.js":
/*!*********************************************************************************!*\
  !*** ./vendor/statamic/cms/resources/js/components/data-list/HasPreferences.js ***!
  \*********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      preferencesPrefix: null
    };
  },
  computed: {
    hasPreferences: function hasPreferences() {
      return this.preferencesPrefix !== null;
    }
  },
  methods: {
    preferencesKey: function preferencesKey(type) {
      return "".concat(this.preferencesPrefix, ".").concat(type);
    },
    getPreference: function getPreference(type) {
      return this.$preferences.get(this.preferencesKey(type));
    },
    setPreference: function setPreference(type, value) {
      return this.$preferences.set(this.preferencesKey(type), value);
    },
    removePreference: function removePreference(type) {
      var value = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
      return this.$preferences.remove(this.preferencesKey(type), value);
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-8[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-8[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/PublishFormRedirect.vue?vue&type=style&index=0&lang=css&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-8[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-8[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/PublishFormRedirect.vue?vue&type=style&index=0&lang=css& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.publish-tab-actions {\n  display: none;\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/runtime/api.js":
/*!*****************************************************!*\
  !*** ./node_modules/css-loader/dist/runtime/api.js ***!
  \*****************************************************/
/***/ ((module) => {



/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
// eslint-disable-next-line func-names
module.exports = function (cssWithMappingToString) {
  var list = []; // return the list of modules as css string

  list.toString = function toString() {
    return this.map(function (item) {
      var content = cssWithMappingToString(item);

      if (item[2]) {
        return "@media ".concat(item[2], " {").concat(content, "}");
      }

      return content;
    }).join("");
  }; // import a list of modules into the list
  // eslint-disable-next-line func-names


  list.i = function (modules, mediaQuery, dedupe) {
    if (typeof modules === "string") {
      // eslint-disable-next-line no-param-reassign
      modules = [[null, modules, ""]];
    }

    var alreadyImportedModules = {};

    if (dedupe) {
      for (var i = 0; i < this.length; i++) {
        // eslint-disable-next-line prefer-destructuring
        var id = this[i][0];

        if (id != null) {
          alreadyImportedModules[id] = true;
        }
      }
    }

    for (var _i = 0; _i < modules.length; _i++) {
      var item = [].concat(modules[_i]);

      if (dedupe && alreadyImportedModules[item[0]]) {
        // eslint-disable-next-line no-continue
        continue;
      }

      if (mediaQuery) {
        if (!item[2]) {
          item[2] = mediaQuery;
        } else {
          item[2] = "".concat(mediaQuery, " and ").concat(item[2]);
        }
      }

      list.push(item);
    }
  };

  return list;
};

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-8[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-8[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/PublishFormRedirect.vue?vue&type=style&index=0&lang=css&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-8[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-8[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/PublishFormRedirect.vue?vue&type=style&index=0&lang=css& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_dist_cjs_js_clonedRuleSet_8_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_8_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_PublishFormRedirect_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-8[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-8[0].rules[0].use[2]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PublishFormRedirect.vue?vue&type=style&index=0&lang=css& */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-8[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-8[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/PublishFormRedirect.vue?vue&type=style&index=0&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_dist_cjs_js_clonedRuleSet_8_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_8_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_PublishFormRedirect_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_css_loader_dist_cjs_js_clonedRuleSet_8_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_8_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_PublishFormRedirect_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js":
/*!****************************************************************************!*\
  !*** ./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js ***!
  \****************************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {



var isOldIE = function isOldIE() {
  var memo;
  return function memorize() {
    if (typeof memo === 'undefined') {
      // Test for IE <= 9 as proposed by Browserhacks
      // @see http://browserhacks.com/#hack-e71d8692f65334173fee715c222cb805
      // Tests for existence of standard globals is to allow style-loader
      // to operate correctly into non-standard environments
      // @see https://github.com/webpack-contrib/style-loader/issues/177
      memo = Boolean(window && document && document.all && !window.atob);
    }

    return memo;
  };
}();

var getTarget = function getTarget() {
  var memo = {};
  return function memorize(target) {
    if (typeof memo[target] === 'undefined') {
      var styleTarget = document.querySelector(target); // Special case to return head of iframe instead of iframe itself

      if (window.HTMLIFrameElement && styleTarget instanceof window.HTMLIFrameElement) {
        try {
          // This will throw an exception if access to iframe is blocked
          // due to cross-origin restrictions
          styleTarget = styleTarget.contentDocument.head;
        } catch (e) {
          // istanbul ignore next
          styleTarget = null;
        }
      }

      memo[target] = styleTarget;
    }

    return memo[target];
  };
}();

var stylesInDom = [];

function getIndexByIdentifier(identifier) {
  var result = -1;

  for (var i = 0; i < stylesInDom.length; i++) {
    if (stylesInDom[i].identifier === identifier) {
      result = i;
      break;
    }
  }

  return result;
}

function modulesToDom(list, options) {
  var idCountMap = {};
  var identifiers = [];

  for (var i = 0; i < list.length; i++) {
    var item = list[i];
    var id = options.base ? item[0] + options.base : item[0];
    var count = idCountMap[id] || 0;
    var identifier = "".concat(id, " ").concat(count);
    idCountMap[id] = count + 1;
    var index = getIndexByIdentifier(identifier);
    var obj = {
      css: item[1],
      media: item[2],
      sourceMap: item[3]
    };

    if (index !== -1) {
      stylesInDom[index].references++;
      stylesInDom[index].updater(obj);
    } else {
      stylesInDom.push({
        identifier: identifier,
        updater: addStyle(obj, options),
        references: 1
      });
    }

    identifiers.push(identifier);
  }

  return identifiers;
}

function insertStyleElement(options) {
  var style = document.createElement('style');
  var attributes = options.attributes || {};

  if (typeof attributes.nonce === 'undefined') {
    var nonce =  true ? __webpack_require__.nc : 0;

    if (nonce) {
      attributes.nonce = nonce;
    }
  }

  Object.keys(attributes).forEach(function (key) {
    style.setAttribute(key, attributes[key]);
  });

  if (typeof options.insert === 'function') {
    options.insert(style);
  } else {
    var target = getTarget(options.insert || 'head');

    if (!target) {
      throw new Error("Couldn't find a style target. This probably means that the value for the 'insert' parameter is invalid.");
    }

    target.appendChild(style);
  }

  return style;
}

function removeStyleElement(style) {
  // istanbul ignore if
  if (style.parentNode === null) {
    return false;
  }

  style.parentNode.removeChild(style);
}
/* istanbul ignore next  */


var replaceText = function replaceText() {
  var textStore = [];
  return function replace(index, replacement) {
    textStore[index] = replacement;
    return textStore.filter(Boolean).join('\n');
  };
}();

function applyToSingletonTag(style, index, remove, obj) {
  var css = remove ? '' : obj.media ? "@media ".concat(obj.media, " {").concat(obj.css, "}") : obj.css; // For old IE

  /* istanbul ignore if  */

  if (style.styleSheet) {
    style.styleSheet.cssText = replaceText(index, css);
  } else {
    var cssNode = document.createTextNode(css);
    var childNodes = style.childNodes;

    if (childNodes[index]) {
      style.removeChild(childNodes[index]);
    }

    if (childNodes.length) {
      style.insertBefore(cssNode, childNodes[index]);
    } else {
      style.appendChild(cssNode);
    }
  }
}

function applyToTag(style, options, obj) {
  var css = obj.css;
  var media = obj.media;
  var sourceMap = obj.sourceMap;

  if (media) {
    style.setAttribute('media', media);
  } else {
    style.removeAttribute('media');
  }

  if (sourceMap && typeof btoa !== 'undefined') {
    css += "\n/*# sourceMappingURL=data:application/json;base64,".concat(btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))), " */");
  } // For old IE

  /* istanbul ignore if  */


  if (style.styleSheet) {
    style.styleSheet.cssText = css;
  } else {
    while (style.firstChild) {
      style.removeChild(style.firstChild);
    }

    style.appendChild(document.createTextNode(css));
  }
}

var singleton = null;
var singletonCounter = 0;

function addStyle(obj, options) {
  var style;
  var update;
  var remove;

  if (options.singleton) {
    var styleIndex = singletonCounter++;
    style = singleton || (singleton = insertStyleElement(options));
    update = applyToSingletonTag.bind(null, style, styleIndex, false);
    remove = applyToSingletonTag.bind(null, style, styleIndex, true);
  } else {
    style = insertStyleElement(options);
    update = applyToTag.bind(null, style, options);

    remove = function remove() {
      removeStyleElement(style);
    };
  }

  update(obj);
  return function updateStyle(newObj) {
    if (newObj) {
      if (newObj.css === obj.css && newObj.media === obj.media && newObj.sourceMap === obj.sourceMap) {
        return;
      }

      update(obj = newObj);
    } else {
      remove();
    }
  };
}

module.exports = function (list, options) {
  options = options || {}; // Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
  // tags it will allow on a page

  if (!options.singleton && typeof options.singleton !== 'boolean') {
    options.singleton = isOldIE();
  }

  list = list || [];
  var lastIdentifiers = modulesToDom(list, options);
  return function update(newList) {
    newList = newList || [];

    if (Object.prototype.toString.call(newList) !== '[object Array]') {
      return;
    }

    for (var i = 0; i < lastIdentifiers.length; i++) {
      var identifier = lastIdentifiers[i];
      var index = getIndexByIdentifier(identifier);
      stylesInDom[index].references--;
    }

    var newLastIdentifiers = modulesToDom(newList, options);

    for (var _i = 0; _i < lastIdentifiers.length; _i++) {
      var _identifier = lastIdentifiers[_i];

      var _index = getIndexByIdentifier(_identifier);

      if (stylesInDom[_index].references === 0) {
        stylesInDom[_index].updater();

        stylesInDom.splice(_index, 1);
      }
    }

    lastIdentifiers = newLastIdentifiers;
  };
};

/***/ }),

/***/ "./resources/js/components/ErrorsListing.vue":
/*!***************************************************!*\
  !*** ./resources/js/components/ErrorsListing.vue ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ErrorsListing_vue_vue_type_template_id_5b6aebbe___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ErrorsListing.vue?vue&type=template&id=5b6aebbe& */ "./resources/js/components/ErrorsListing.vue?vue&type=template&id=5b6aebbe&");
/* harmony import */ var _ErrorsListing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ErrorsListing.vue?vue&type=script&lang=js& */ "./resources/js/components/ErrorsListing.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ErrorsListing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ErrorsListing_vue_vue_type_template_id_5b6aebbe___WEBPACK_IMPORTED_MODULE_0__.render,
  _ErrorsListing_vue_vue_type_template_id_5b6aebbe___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/ErrorsListing.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/PublishFormRedirect.vue":
/*!*********************************************************!*\
  !*** ./resources/js/components/PublishFormRedirect.vue ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _PublishFormRedirect_vue_vue_type_template_id_488745d4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./PublishFormRedirect.vue?vue&type=template&id=488745d4& */ "./resources/js/components/PublishFormRedirect.vue?vue&type=template&id=488745d4&");
/* harmony import */ var _PublishFormRedirect_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./PublishFormRedirect.vue?vue&type=script&lang=js& */ "./resources/js/components/PublishFormRedirect.vue?vue&type=script&lang=js&");
/* harmony import */ var _PublishFormRedirect_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./PublishFormRedirect.vue?vue&type=style&index=0&lang=css& */ "./resources/js/components/PublishFormRedirect.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _PublishFormRedirect_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _PublishFormRedirect_vue_vue_type_template_id_488745d4___WEBPACK_IMPORTED_MODULE_0__.render,
  _PublishFormRedirect_vue_vue_type_template_id_488745d4___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/PublishFormRedirect.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/RedirectListing.vue":
/*!*****************************************************!*\
  !*** ./resources/js/components/RedirectListing.vue ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _RedirectListing_vue_vue_type_template_id_3a32536d___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RedirectListing.vue?vue&type=template&id=3a32536d& */ "./resources/js/components/RedirectListing.vue?vue&type=template&id=3a32536d&");
/* harmony import */ var _RedirectListing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./RedirectListing.vue?vue&type=script&lang=js& */ "./resources/js/components/RedirectListing.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _RedirectListing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _RedirectListing_vue_vue_type_template_id_3a32536d___WEBPACK_IMPORTED_MODULE_0__.render,
  _RedirectListing_vue_vue_type_template_id_3a32536d___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/RedirectListing.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./vendor/statamic/cms/resources/js/components/Listing.vue":
/*!*****************************************************************!*\
  !*** ./vendor/statamic/cms/resources/js/components/Listing.vue ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Listing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Listing.vue?vue&type=script&lang=js& */ "./vendor/statamic/cms/resources/js/components/Listing.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns
;



/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _Listing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "vendor/statamic/cms/resources/js/components/Listing.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/ErrorsListing.vue?vue&type=script&lang=js&":
/*!****************************************************************************!*\
  !*** ./resources/js/components/ErrorsListing.vue?vue&type=script&lang=js& ***!
  \****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ErrorsListing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ErrorsListing.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ErrorsListing.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ErrorsListing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/PublishFormRedirect.vue?vue&type=script&lang=js&":
/*!**********************************************************************************!*\
  !*** ./resources/js/components/PublishFormRedirect.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PublishFormRedirect_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PublishFormRedirect.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/PublishFormRedirect.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PublishFormRedirect_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/RedirectListing.vue?vue&type=script&lang=js&":
/*!******************************************************************************!*\
  !*** ./resources/js/components/RedirectListing.vue?vue&type=script&lang=js& ***!
  \******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedirectListing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./RedirectListing.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RedirectListing.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RedirectListing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./vendor/statamic/cms/resources/js/components/Listing.vue?vue&type=script&lang=js&":
/*!******************************************************************************************!*\
  !*** ./vendor/statamic/cms/resources/js/components/Listing.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Listing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Listing.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./vendor/statamic/cms/resources/js/components/Listing.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Listing_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/PublishFormRedirect.vue?vue&type=style&index=0&lang=css&":
/*!******************************************************************************************!*\
  !*** ./resources/js/components/PublishFormRedirect.vue?vue&type=style&index=0&lang=css& ***!
  \******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_dist_cjs_js_clonedRuleSet_8_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_8_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_PublishFormRedirect_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader/dist/cjs.js!../../../node_modules/css-loader/dist/cjs.js??clonedRuleSet-8[0].rules[0].use[1]!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-8[0].rules[0].use[2]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PublishFormRedirect.vue?vue&type=style&index=0&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/dist/cjs.js??clonedRuleSet-8[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-8[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/PublishFormRedirect.vue?vue&type=style&index=0&lang=css&");


/***/ }),

/***/ "./resources/js/components/ErrorsListing.vue?vue&type=template&id=5b6aebbe&":
/*!**********************************************************************************!*\
  !*** ./resources/js/components/ErrorsListing.vue?vue&type=template&id=5b6aebbe& ***!
  \**********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ErrorsListing_vue_vue_type_template_id_5b6aebbe___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ErrorsListing_vue_vue_type_template_id_5b6aebbe___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ErrorsListing_vue_vue_type_template_id_5b6aebbe___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ErrorsListing.vue?vue&type=template&id=5b6aebbe& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ErrorsListing.vue?vue&type=template&id=5b6aebbe&");


/***/ }),

/***/ "./resources/js/components/PublishFormRedirect.vue?vue&type=template&id=488745d4&":
/*!****************************************************************************************!*\
  !*** ./resources/js/components/PublishFormRedirect.vue?vue&type=template&id=488745d4& ***!
  \****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PublishFormRedirect_vue_vue_type_template_id_488745d4___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PublishFormRedirect_vue_vue_type_template_id_488745d4___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PublishFormRedirect_vue_vue_type_template_id_488745d4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PublishFormRedirect.vue?vue&type=template&id=488745d4& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/PublishFormRedirect.vue?vue&type=template&id=488745d4&");


/***/ }),

/***/ "./resources/js/components/RedirectListing.vue?vue&type=template&id=3a32536d&":
/*!************************************************************************************!*\
  !*** ./resources/js/components/RedirectListing.vue?vue&type=template&id=3a32536d& ***!
  \************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RedirectListing_vue_vue_type_template_id_3a32536d___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RedirectListing_vue_vue_type_template_id_3a32536d___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_RedirectListing_vue_vue_type_template_id_3a32536d___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./RedirectListing.vue?vue&type=template&id=3a32536d& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RedirectListing.vue?vue&type=template&id=3a32536d&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ErrorsListing.vue?vue&type=template&id=5b6aebbe&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/ErrorsListing.vue?vue&type=template&id=5b6aebbe& ***!
  \*************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _vm.initializing
        ? _c("div", { staticClass: "card loading" }, [_c("loading-graphic")], 1)
        : _vm._e(),
      _vm._v(" "),
      !_vm.initializing
        ? _c("data-list", {
            ref: "datalist",
            attrs: {
              rows: _vm.items,
              columns: _vm.columns,
              sort: false,
              "sort-column": _vm.sortColumn,
              "sort-direction": _vm.sortDirection
            },
            on: {
              "visible-columns-updated": function($event) {
                _vm.visibleColumns = $event
              }
            },
            scopedSlots: _vm._u(
              [
                {
                  key: "default",
                  fn: function(ref) {
                    var hasSelections = ref.hasSelections
                    return _c(
                      "div",
                      {},
                      [
                        _c(
                          "div",
                          { staticClass: "card overflow-hidden p-0 relative" },
                          [
                            _c(
                              "div",
                              {
                                staticClass:
                                  "flex flex-wrap items-center justify-between px-2 pb-2 text-sm border-b"
                              },
                              [
                                _c("data-list-filter-presets", {
                                  ref: "presets",
                                  attrs: {
                                    "active-preset": _vm.activePreset,
                                    "active-preset-payload":
                                      _vm.activePresetPayload,
                                    "active-filters": _vm.activeFilters,
                                    "has-active-filters": _vm.hasActiveFilters,
                                    "preferences-prefix": _vm.preferencesPrefix,
                                    "search-query": _vm.searchQuery
                                  },
                                  on: {
                                    selected: _vm.selectPreset,
                                    reset: _vm.filtersReset
                                  }
                                }),
                                _vm._v(" "),
                                _c("div", { staticClass: "w-full flex-1" }),
                                _vm._v(" "),
                                _c(
                                  "div",
                                  { staticClass: "flex space-x-2 mt-2" },
                                  [
                                    _c("button", {
                                      directives: [
                                        {
                                          name: "show",
                                          rawName: "v-show",
                                          value: _vm.isDirty,
                                          expression: "isDirty"
                                        }
                                      ],
                                      staticClass: "btn btn-sm ml-2",
                                      domProps: {
                                        textContent: _vm._s(_vm.__("Reset"))
                                      },
                                      on: {
                                        click: function($event) {
                                          return _vm.$refs.presets.refreshPreset()
                                        }
                                      }
                                    }),
                                    _vm._v(" "),
                                    _c("button", {
                                      directives: [
                                        {
                                          name: "show",
                                          rawName: "v-show",
                                          value: _vm.isDirty,
                                          expression: "isDirty"
                                        }
                                      ],
                                      staticClass: "btn btn-sm ml-2",
                                      domProps: {
                                        textContent: _vm._s(_vm.__("Save"))
                                      },
                                      on: {
                                        click: function($event) {
                                          return _vm.$refs.presets.savePreset()
                                        }
                                      }
                                    }),
                                    _vm._v(" "),
                                    _c("data-list-column-picker", {
                                      attrs: {
                                        "preferences-key": _vm.preferencesKey(
                                          "columns"
                                        )
                                      }
                                    })
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _c(
                                  "a",
                                  {
                                    staticClass:
                                      "ml-2 mt-2 btn btn-sm flex items-center",
                                    attrs: {
                                      href: _vm.cp_url("redirect/errors/clear")
                                    }
                                  },
                                  [_vm._v("Clear all errors")]
                                )
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c(
                              "div",
                              [
                                _c("data-list-filters", {
                                  ref: "filters",
                                  attrs: {
                                    filters: _vm.filters,
                                    "active-preset": _vm.activePreset,
                                    "active-preset-payload":
                                      _vm.activePresetPayload,
                                    "active-filters": _vm.activeFilters,
                                    "active-filter-badges":
                                      _vm.activeFilterBadges,
                                    "active-count": _vm.activeFilterCount,
                                    "search-query": _vm.searchQuery,
                                    "is-searching": true,
                                    "saves-presets": true,
                                    "preferences-prefix": _vm.preferencesPrefix
                                  },
                                  on: {
                                    changed: _vm.filterChanged,
                                    saved: function($event) {
                                      return _vm.$refs.presets.setPreset($event)
                                    },
                                    deleted: function($event) {
                                      return _vm.$refs.presets.refreshPresets()
                                    }
                                  }
                                })
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c("div", {
                              directives: [
                                {
                                  name: "show",
                                  rawName: "v-show",
                                  value: _vm.items.length === 0,
                                  expression: "items.length === 0"
                                }
                              ],
                              staticClass: "p-6 text-center text-gray-500",
                              domProps: {
                                textContent: _vm._s(_vm.__("No results"))
                              }
                            }),
                            _vm._v(" "),
                            _c(
                              "div",
                              {
                                staticClass: "overflow-x-auto overflow-y-hidden"
                              },
                              [
                                _c("data-list-table", {
                                  directives: [
                                    {
                                      name: "show",
                                      rawName: "v-show",
                                      value: _vm.items.length,
                                      expression: "items.length"
                                    }
                                  ],
                                  attrs: {
                                    "allow-bulk-actions": false,
                                    loading: _vm.loading,
                                    reorderable: false,
                                    sortable: true,
                                    "toggle-selection-on-row-click": false,
                                    "allow-column-picker": true,
                                    "column-preferences-key": _vm.preferencesKey(
                                      "columns"
                                    )
                                  },
                                  on: { sorted: _vm.sorted },
                                  scopedSlots: _vm._u(
                                    [
                                      {
                                        key: "cell-url",
                                        fn: function(ref) {
                                          var error = ref.row
                                          return [
                                            _c(
                                              "a",
                                              {
                                                staticClass:
                                                  "text-blue hover:text-blue-dark",
                                                staticStyle: {
                                                  "word-break": "break-all"
                                                },
                                                attrs: {
                                                  href: _vm.cp_url(
                                                    "redirect/errors/" +
                                                      error.id
                                                  )
                                                }
                                              },
                                              [_vm._v(_vm._s(error.url))]
                                            )
                                          ]
                                        }
                                      },
                                      {
                                        key: "cell-lastSeenAt",
                                        fn: function(ref) {
                                          var error = ref.row
                                          return [
                                            _c("span", {
                                              domProps: {
                                                innerHTML: _vm._s(
                                                  _vm.relativeDate(
                                                    error.lastSeenAt
                                                  )
                                                )
                                              }
                                            })
                                          ]
                                        }
                                      },
                                      {
                                        key: "cell-handled",
                                        fn: function(ref) {
                                          var error = ref.row
                                          return [
                                            error.handled
                                              ? _c("div", {
                                                  staticClass:
                                                    "bg-green block h-3 w-2 mr-auto rounded-full"
                                                })
                                              : _c("div", {
                                                  staticClass:
                                                    "bg-red block h-3 w-2 mr-auto rounded-full"
                                                })
                                          ]
                                        }
                                      },
                                      {
                                        key: "cell-handledDestination",
                                        fn: function(ref) {
                                          var error = ref.row
                                          return [
                                            _c(
                                              "span",
                                              {
                                                staticStyle: {
                                                  "word-break": "break-all"
                                                }
                                              },
                                              [
                                                _vm._v(
                                                  _vm._s(
                                                    error.handledDestination
                                                  )
                                                )
                                              ]
                                            )
                                          ]
                                        }
                                      },
                                      {
                                        key: "actions",
                                        fn: function(ref) {
                                          var error = ref.row
                                          var index = ref.index
                                          return [
                                            !error.handled
                                              ? _c(
                                                  "a",
                                                  {
                                                    staticClass:
                                                      "text-blue inline-block",
                                                    attrs: {
                                                      href:
                                                        _vm.cp_url(
                                                          "redirect/redirects/create"
                                                        ) +
                                                        "?source=" +
                                                        encodeURI(error.url)
                                                    }
                                                  },
                                                  [
                                                    _c(
                                                      "svg",
                                                      {
                                                        staticClass:
                                                          "w-4 h-4 mr-2",
                                                        attrs: {
                                                          "aria-hidden": "true",
                                                          focusable: "false",
                                                          "data-prefix": "far",
                                                          "data-icon": "plus",
                                                          role: "img",
                                                          xmlns:
                                                            "http://www.w3.org/2000/svg",
                                                          viewBox: "0 0 384 512"
                                                        }
                                                      },
                                                      [
                                                        _c("path", {
                                                          attrs: {
                                                            fill:
                                                              "currentColor",
                                                            d:
                                                              "M368 224H224V80c0-8.84-7.16-16-16-16h-32c-8.84 0-16 7.16-16 16v144H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h144v144c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16V288h144c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z"
                                                          }
                                                        })
                                                      ]
                                                    )
                                                  ]
                                                )
                                              : _vm._e()
                                          ]
                                        }
                                      }
                                    ],
                                    null,
                                    true
                                  )
                                })
                              ],
                              1
                            )
                          ]
                        ),
                        _vm._v(" "),
                        _c("data-list-pagination", {
                          staticClass: "mt-3",
                          attrs: {
                            "resource-meta": _vm.meta,
                            "per-page": _vm.perPage
                          },
                          on: {
                            "page-selected": _vm.selectPage,
                            "per-page-changed": _vm.changePerPage
                          }
                        })
                      ],
                      1
                    )
                  }
                }
              ],
              null,
              false,
              2208292533
            )
          })
        : _vm._e()
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/PublishFormRedirect.vue?vue&type=template&id=488745d4&":
/*!*******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/PublishFormRedirect.vue?vue&type=template&id=488745d4& ***!
  \*******************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("publish-form", {
    attrs: {
      title: _vm.title,
      action: _vm.action,
      blueprint: _vm.blueprint,
      meta: _vm.meta,
      values: _vm.values
    },
    on: { saved: _vm.saved }
  })
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RedirectListing.vue?vue&type=template&id=3a32536d&":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/RedirectListing.vue?vue&type=template&id=3a32536d& ***!
  \***************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _vm.initializing
        ? _c("div", { staticClass: "card loading" }, [_c("loading-graphic")], 1)
        : _vm._e(),
      _vm._v(" "),
      !_vm.initializing
        ? _c("data-list", {
            ref: "datalist",
            attrs: {
              rows: _vm.items,
              columns: _vm.columns,
              sort: false,
              "sort-column": _vm.sortColumn,
              "sort-direction": _vm.sortDirection
            },
            on: {
              "visible-columns-updated": function($event) {
                _vm.visibleColumns = $event
              }
            },
            scopedSlots: _vm._u(
              [
                {
                  key: "default",
                  fn: function(ref) {
                    var hasSelections = ref.hasSelections
                    return _c(
                      "div",
                      {},
                      [
                        _c(
                          "div",
                          { staticClass: "card overflow-hidden p-0 relative" },
                          [
                            _c(
                              "div",
                              {
                                staticClass:
                                  "flex flex-wrap items-center justify-between px-2 pb-2 text-sm border-b"
                              },
                              [
                                _c("data-list-filter-presets", {
                                  ref: "presets",
                                  attrs: {
                                    "active-preset": _vm.activePreset,
                                    "preferences-prefix": _vm.preferencesPrefix
                                  },
                                  on: {
                                    selected: _vm.selectPreset,
                                    reset: _vm.filtersReset
                                  }
                                })
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c(
                              "div",
                              [
                                _c("data-list-filters", {
                                  attrs: {
                                    filters: _vm.filters,
                                    "active-preset": _vm.activePreset,
                                    "active-preset-payload":
                                      _vm.activePresetPayload,
                                    "active-filters": _vm.activeFilters,
                                    "active-filter-badges":
                                      _vm.activeFilterBadges,
                                    "active-count": _vm.activeFilterCount,
                                    "search-query": _vm.searchQuery,
                                    "saves-presets": true,
                                    "preferences-prefix": _vm.preferencesPrefix
                                  },
                                  on: {
                                    "filter-changed": _vm.filterChanged,
                                    "search-changed": _vm.searchChanged,
                                    saved: function($event) {
                                      return _vm.$refs.presets.setPreset($event)
                                    },
                                    deleted: function($event) {
                                      return _vm.$refs.presets.refreshPresets()
                                    },
                                    "restore-preset": function($event) {
                                      return _vm.$refs.presets.viewPreset(
                                        $event
                                      )
                                    },
                                    reset: _vm.filtersReset
                                  }
                                })
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c("div", {
                              directives: [
                                {
                                  name: "show",
                                  rawName: "v-show",
                                  value: _vm.items.length === 0,
                                  expression: "items.length === 0"
                                }
                              ],
                              staticClass: "p-3 text-center text-grey-50",
                              domProps: {
                                textContent: _vm._s(_vm.__("No results"))
                              }
                            }),
                            _vm._v(" "),
                            _c("data-list-table", {
                              directives: [
                                {
                                  name: "show",
                                  rawName: "v-show",
                                  value: _vm.items.length,
                                  expression: "items.length"
                                }
                              ],
                              attrs: {
                                "allow-bulk-actions": false,
                                loading: _vm.loading,
                                reorderable: false,
                                sortable: true,
                                "toggle-selection-on-row-click": false,
                                "allow-column-picker": true,
                                "column-preferences-key": _vm.preferencesKey(
                                  "columns"
                                )
                              },
                              on: { sorted: _vm.sorted },
                              scopedSlots: _vm._u(
                                [
                                  {
                                    key: "cell-enabled",
                                    fn: function(ref) {
                                      var redirect = ref.row
                                      return [
                                        redirect.enabled
                                          ? _c(
                                              "div",
                                              {
                                                staticClass:
                                                  "status-index-field select-none status-published"
                                              },
                                              [_vm._v("Enabled")]
                                            )
                                          : _c(
                                              "div",
                                              {
                                                staticClass:
                                                  "status-index-field select-none status-draft bg-red-100"
                                              },
                                              [_vm._v("Disabled")]
                                            )
                                      ]
                                    }
                                  },
                                  {
                                    key: "cell-source",
                                    fn: function(ref) {
                                      var redirect = ref.row
                                      return [
                                        _c(
                                          "a",
                                          {
                                            staticStyle: {
                                              "word-break": "break-all"
                                            },
                                            attrs: {
                                              href: _vm.cp_url(
                                                "redirect/redirects/" +
                                                  redirect.id
                                              )
                                            }
                                          },
                                          [_vm._v(_vm._s(redirect.source))]
                                        )
                                      ]
                                    }
                                  },
                                  {
                                    key: "actions",
                                    fn: function(ref) {
                                      var redirect = ref.row
                                      var index = ref.index
                                      return [
                                        _c(
                                          "dropdown-list",
                                          [
                                            _c("dropdown-item", {
                                              attrs: {
                                                text: _vm.__("Edit"),
                                                redirect: _vm.cp_url(
                                                  "redirect/redirects/" +
                                                    redirect.id
                                                )
                                              }
                                            }),
                                            _vm._v(" "),
                                            _c(
                                              "dropdown-item",
                                              {
                                                staticClass: "warning",
                                                attrs: {
                                                  text: _vm.__("Delete")
                                                },
                                                on: {
                                                  click: function($event) {
                                                    _vm.$refs[
                                                      "deleter_" + redirect.id
                                                    ].confirm()
                                                  }
                                                }
                                              },
                                              [
                                                _c("resource-deleter", {
                                                  ref: "deleter_" + redirect.id,
                                                  attrs: {
                                                    resource: redirect,
                                                    reload: true
                                                  },
                                                  on: {
                                                    deleted: function($event) {
                                                      return _vm.removeRow(
                                                        redirect.id
                                                      )
                                                    }
                                                  }
                                                })
                                              ],
                                              1
                                            )
                                          ],
                                          1
                                        )
                                      ]
                                    }
                                  }
                                ],
                                null,
                                true
                              )
                            })
                          ],
                          1
                        ),
                        _vm._v(" "),
                        _c("data-list-pagination", {
                          staticClass: "mt-3",
                          attrs: {
                            "resource-meta": _vm.meta,
                            "per-page": _vm.perPage
                          },
                          on: {
                            "page-selected": _vm.selectPage,
                            "per-page-changed": _vm.changePerPage
                          }
                        })
                      ],
                      1
                    )
                  }
                }
              ],
              null,
              false,
              3149455362
            )
          })
        : _vm._e()
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ normalizeComponent)
/* harmony export */ });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        )
      }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			id: moduleId,
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!****************************!*\
  !*** ./resources/js/cp.js ***!
  \****************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_PublishFormRedirect__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/PublishFormRedirect */ "./resources/js/components/PublishFormRedirect.vue");
/* harmony import */ var _components_RedirectListing__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/RedirectListing */ "./resources/js/components/RedirectListing.vue");
/* harmony import */ var _components_ErrorsListing__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/ErrorsListing */ "./resources/js/components/ErrorsListing.vue");



Statamic.$components.register("publish-form-redirect", _components_PublishFormRedirect__WEBPACK_IMPORTED_MODULE_0__["default"]);
Statamic.$components.register("errors-listing", _components_ErrorsListing__WEBPACK_IMPORTED_MODULE_2__["default"]);
Statamic.$components.register("redirect-listing", _components_RedirectListing__WEBPACK_IMPORTED_MODULE_1__["default"]);
})();

/******/ })()
;