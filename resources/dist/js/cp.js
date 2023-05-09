(()=>{"use strict";var e={650:(e,t,r)=>{r.d(t,{Z:()=>n});var i=r(645),s=r.n(i)()((function(e){return e[1]}));s.push([e.id,".publish-tab-actions{display:none}",""]);const n=s},645:e=>{e.exports=function(e){var t=[];return t.toString=function(){return this.map((function(t){var r=e(t);return t[2]?"@media ".concat(t[2]," {").concat(r,"}"):r})).join("")},t.i=function(e,r,i){"string"==typeof e&&(e=[[null,e,""]]);var s={};if(i)for(var n=0;n<this.length;n++){var a=this[n][0];null!=a&&(s[a]=!0)}for(var o=0;o<e.length;o++){var l=[].concat(e[o]);i&&s[l[0]]||(r&&(l[2]?l[2]="".concat(r," and ").concat(l[2]):l[2]=r),t.push(l))}},t}},379:(e,t,r)=>{var i,s=function(){return void 0===i&&(i=Boolean(window&&document&&document.all&&!window.atob)),i},n=function(){var e={};return function(t){if(void 0===e[t]){var r=document.querySelector(t);if(window.HTMLIFrameElement&&r instanceof window.HTMLIFrameElement)try{r=r.contentDocument.head}catch(e){r=null}e[t]=r}return e[t]}}(),a=[];function o(e){for(var t=-1,r=0;r<a.length;r++)if(a[r].identifier===e){t=r;break}return t}function l(e,t){for(var r={},i=[],s=0;s<e.length;s++){var n=e[s],l=t.base?n[0]+t.base:n[0],c=r[l]||0,u="".concat(l," ").concat(c);r[l]=c+1;var d=o(u),f={css:n[1],media:n[2],sourceMap:n[3]};-1!==d?(a[d].references++,a[d].updater(f)):a.push({identifier:u,updater:m(f,t),references:1}),i.push(u)}return i}function c(e){var t=document.createElement("style"),i=e.attributes||{};if(void 0===i.nonce){var s=r.nc;s&&(i.nonce=s)}if(Object.keys(i).forEach((function(e){t.setAttribute(e,i[e])})),"function"==typeof e.insert)e.insert(t);else{var a=n(e.insert||"head");if(!a)throw new Error("Couldn't find a style target. This probably means that the value for the 'insert' parameter is invalid.");a.appendChild(t)}return t}var u,d=(u=[],function(e,t){return u[e]=t,u.filter(Boolean).join("\n")});function f(e,t,r,i){var s=r?"":i.media?"@media ".concat(i.media," {").concat(i.css,"}"):i.css;if(e.styleSheet)e.styleSheet.cssText=d(t,s);else{var n=document.createTextNode(s),a=e.childNodes;a[t]&&e.removeChild(a[t]),a.length?e.insertBefore(n,a[t]):e.appendChild(n)}}function h(e,t,r){var i=r.css,s=r.media,n=r.sourceMap;if(s?e.setAttribute("media",s):e.removeAttribute("media"),n&&"undefined"!=typeof btoa&&(i+="\n/*# sourceMappingURL=data:application/json;base64,".concat(btoa(unescape(encodeURIComponent(JSON.stringify(n))))," */")),e.styleSheet)e.styleSheet.cssText=i;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(i))}}var p=null,v=0;function m(e,t){var r,i,s;if(t.singleton){var n=v++;r=p||(p=c(t)),i=f.bind(null,r,n,!1),s=f.bind(null,r,n,!0)}else r=c(t),i=h.bind(null,r,t),s=function(){!function(e){if(null===e.parentNode)return!1;e.parentNode.removeChild(e)}(r)};return i(e),function(t){if(t){if(t.css===e.css&&t.media===e.media&&t.sourceMap===e.sourceMap)return;i(e=t)}else s()}}e.exports=function(e,t){(t=t||{}).singleton||"boolean"==typeof t.singleton||(t.singleton=s());var r=l(e=e||[],t);return function(e){if(e=e||[],"[object Array]"===Object.prototype.toString.call(e)){for(var i=0;i<r.length;i++){var s=o(r[i]);a[s].references--}for(var n=l(e,t),c=0;c<r.length;c++){var u=o(r[c]);0===a[u].references&&(a[u].updater(),a.splice(u,1))}r=n}}}}},t={};function r(i){var s=t[i];if(void 0!==s)return s.exports;var n=t[i]={id:i,exports:{}};return e[i](n,n.exports,r),n.exports}r.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return r.d(t,{a:t}),t},r.d=(e,t)=>{for(var i in t)r.o(t,i)&&!r.o(e,i)&&Object.defineProperty(e,i,{enumerable:!0,get:t[i]})},r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{const e={props:{title:String,action:String,blueprint:Array,meta:Array,redirectTo:String,values:Array},methods:{saved:function(e){var t=this;setTimeout((function(){return t.redirect(e.data)}),600)},redirect:function(e){window.location.href=this.createEditRoute(e)},createEditRoute:function(e){return this.redirectTo.replace("x-redirect",e)}}};var t=r(379),i=r.n(t),s=r(650),n={insert:"head",singleton:!1};i()(s.Z,n);s.Z.locals;function a(e,t,r,i,s,n,a,o){var l,c="function"==typeof e?e.options:e;if(t&&(c.render=t,c.staticRenderFns=r,c._compiled=!0),i&&(c.functional=!0),n&&(c._scopeId="data-v-"+n),a?(l=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),s&&s.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(a)},c._ssrRegister=l):s&&(l=o?function(){s.call(this,(c.functional?this.parent:this).$root.$options.shadowRoot)}:s),l)if(c.functional){c._injectStyles=l;var u=c.render;c.render=function(e,t){return l.call(t),u(e,t)}}else{var d=c.beforeCreate;c.beforeCreate=d?[].concat(d,l):[l]}return{exports:e,options:c}}const o=a(e,(function(){var e=this,t=e.$createElement;return(e._self._c||t)("publish-form",{attrs:{title:e.title,action:e.action,blueprint:e.blueprint,meta:e.meta,values:e.values},on:{saved:e.saved}})}),[],!1,null,null,null).exports;function l(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);t&&(i=i.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,i)}return r}function c(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}const u=a({mixins:[{methods:{actionStarted:function(){this.loading=!0},actionCompleted:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};this.loading=!1,!1!==e&&(this.$events.$emit("clear-selections"),this.$events.$emit("reset-action-modals"),!1!==t.message&&this.$toast.success(t.message||__("Action completed")),this.afterActionSuccessfullyCompleted())},afterActionSuccessfullyCompleted:function(){this.request()}}},{data:function(){return{activePreset:null,activePresetPayload:{},searchQuery:"",activeFilters:{},activeFilterBadges:{}}},computed:{activeFilterCount:function(){var e=Object.keys(this.activeFilters).length;return this.activeFilters.hasOwnProperty("fields")&&(e=e+Object.keys(this.activeFilters.fields).filter((function(e){return"badge"!=e})).length-1),e},hasActiveFilters:function(){return this.activeFilterCount>0}},methods:{searchChanged:function(e){this.searchQuery=e},hasFields:function(e){for(var t in e)if(e[t])return!0;return!1},filterChanged:function(e){var t=e.handle,r=e.values,i=!(arguments.length>1&&void 0!==arguments[1])||arguments[1];r&&this.hasFields(r)?Vue.set(this.activeFilters,t,r):Vue.delete(this.activeFilters,t),i&&this.unselectAllItems()},filtersChanged:function(e){for(var t in this.activeFilters={},e){var r=e[t];this.filterChanged({handle:t,values:r},!1)}this.unselectAllItems()},filtersReset:function(){this.activePreset=null,this.activePresetPayload={},this.searchQuery="",this.activeFilters={},this.activeFilterBadges={}},unselectAllItems:function(){this.$refs.dataList&&this.$refs.dataList.clearSelections()},selectPreset:function(e,t){this.activePreset=e,this.activePresetPayload=t,this.searchQuery=t.query,this.filtersChanged(t.filters)},autoApplyFilters:function(e){if(e){var t={};e.filter((function(e){return!_.isEmpty(e.auto_apply)})).forEach((function(e){t[e.handle]=e.auto_apply})),this.activeFilters=t}}}},{props:{initialPerPage:{type:Number,default:function(){return Statamic.$config.get("paginationSize")}}},data:function(){return{perPage:this.initialPerPage,page:1}},mounted:function(){this.setInitialPerPage()},methods:{setInitialPerPage:function(){this.hasPreferences&&(this.perPage=this.getPreference("per_page")||this.initialPerPage)},changePerPage:function(e){var t=this;e=parseInt(e),(this.hasPreferences?this.setPreference("per_page",e!=this.initialPerPage?e:null):Promise.resolve()).then((function(r){t.perPage=e,t.resetPage()}))},selectPage:function(e){this.page=e},resetPage:function(){this.page=1}}},{data:function(){return{preferencesPrefix:null}},computed:{hasPreferences:function(){return null!==this.preferencesPrefix}},methods:{preferencesKey:function(e){return"".concat(this.preferencesPrefix,".").concat(e)},getPreference:function(e){return this.$preferences.get(this.preferencesKey(e))},setPreference:function(e,t){return this.$preferences.set(this.preferencesKey(e),t)},removePreference:function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;return this.$preferences.remove(this.preferencesKey(e),t)}}}],props:{initialSortColumn:String,initialSortDirection:String,initialColumns:{type:Array,default:function(){return[]}},filters:Array,actionUrl:String},data:function(){return{source:null,initializing:!0,loading:!0,items:[],columns:this.initialColumns,visibleColumns:this.initialColumns.filter((function(e){return e.visible})),sortColumn:this.initialSortColumn,sortDirection:this.initialSortDirection,meta:null}},computed:{parameters:function(){return Object.assign({sort:this.sortColumn,order:this.sortDirection,page:this.page,perPage:this.perPage,search:this.searchQuery,filters:this.activeFilterParameters,columns:this.visibleColumns.map((function(e){return e.field})).join(",")},this.additionalParameters)},activeFilterParameters:function(){return utf8btoa(JSON.stringify(this.activeFilters))},additionalParameters:function(){return{}},shouldRequestFirstPage:function(){return this.page>1&&0===this.items.length&&(this.page=1,!0)}},created:function(){this.autoApplyFilters(this.filters),this.request()},watch:{parameters:{deep:!0,handler:function(e,t){t.search===e.search&&JSON.stringify(t)!==JSON.stringify(e)&&this.request()}},loading:{immediate:!0,handler:function(e){this.$progress.loading(this.listingKey,e)}},searchQuery:function(e){this.sortColumn=null,this.sortDirection=null,this.resetPage(),this.request()}},methods:{request:function(){var e=this;this.requestUrl?(this.loading=!0,this.source&&this.source.cancel(),this.source=this.$axios.CancelToken.source(),this.$axios.get(this.requestUrl,{params:this.parameters,cancelToken:this.source.token}).then((function(t){if(e.columns=t.data.meta.columns,e.activeFilterBadges=function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?l(Object(r),!0).forEach((function(t){c(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):l(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},t.data.meta.activeFilterBadges),e.items=Object.values(t.data.data),e.meta=t.data.meta,e.shouldRequestFirstPage)return e.request();e.loading=!1,e.initializing=!1,e.afterRequestCompleted()})).catch((function(t){e.$axios.isCancel(t)||(e.loading=!1,e.initializing=!1,e.$toast.error(t.response?t.response.data.message:__("Something went wrong"),{duration:null}))}))):this.loading=!1},afterRequestCompleted:function(e){},sorted:function(e,t){this.sortColumn=e,this.sortDirection=t},removeRow:function(e){var t=e.id,r=_.indexOf(this.rows,_.findWhere(this.rows,{id:t}));this.rows.splice(r,1),0===this.rows.length&&location.reload()}}},undefined,undefined,!1,null,null,null).exports;const d=a({mixins:[u],data:function(){return{listingKey:"redirects",preferencesPrefix:"redirect.redirects",requestUrl:cp_url("redirect/api/redirects"),currentSite:this.site,initialSite:this.site,columns:this.columns}},watch:{activeFilters:{deep:!0,handler:function(e){this.currentSite=e.site?e.site.site:null}},site:function(e){this.currentSite=e},currentSite:function(e){this.setSiteFilter(e),this.$emit("site-changed",e)}},methods:{setSiteFilter:function(e){this.filterChanged({handle:"site",values:{site:e}})},removeRow:function(e){var t=e.id,r=_.indexOf(this.items,_.findWhere(this.items,{id:t}));this.items.splice(r,1),0===this.items.length&&location.reload();var i=this;Object.keys(this.$refs).forEach((function(e){e.includes("deleter_")&&void 0!==i.$refs[e]&&i.$refs[e].cancel()}))}}},(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",[e.initializing?r("div",{staticClass:"card loading"},[r("loading-graphic")],1):e._e(),e._v(" "),e.initializing?e._e():r("data-list",{ref:"datalist",attrs:{rows:e.items,columns:e.columns,sort:!1,"sort-column":e.sortColumn,"sort-direction":e.sortDirection},on:{"visible-columns-updated":function(t){e.visibleColumns=t}},scopedSlots:e._u([{key:"default",fn:function(t){t.hasSelections;return r("div",{},[r("div",{staticClass:"card overflow-hidden p-0 relative"},[r("div",{staticClass:"flex flex-wrap items-center justify-between px-2 pb-2 text-sm border-b"},[r("data-list-filter-presets",{ref:"presets",attrs:{"active-preset":e.activePreset,"preferences-prefix":e.preferencesPrefix},on:{selected:e.selectPreset,reset:e.filtersReset}})],1),e._v(" "),r("div",[r("data-list-filters",{attrs:{filters:e.filters,"active-preset":e.activePreset,"active-preset-payload":e.activePresetPayload,"active-filters":e.activeFilters,"active-filter-badges":e.activeFilterBadges,"active-count":e.activeFilterCount,"search-query":e.searchQuery,"saves-presets":!0,"preferences-prefix":e.preferencesPrefix},on:{"filter-changed":e.filterChanged,"search-changed":e.searchChanged,saved:function(t){return e.$refs.presets.setPreset(t)},deleted:function(t){return e.$refs.presets.refreshPresets()},"restore-preset":function(t){return e.$refs.presets.viewPreset(t)},reset:e.filtersReset}})],1),e._v(" "),r("div",{directives:[{name:"show",rawName:"v-show",value:0===e.items.length,expression:"items.length === 0"}],staticClass:"p-3 text-center text-grey-50",domProps:{textContent:e._s(e.__("No results"))}}),e._v(" "),r("data-list-table",{directives:[{name:"show",rawName:"v-show",value:e.items.length,expression:"items.length"}],attrs:{"allow-bulk-actions":!1,loading:e.loading,reorderable:!1,sortable:!0,"toggle-selection-on-row-click":!1,"allow-column-picker":!0,"column-preferences-key":e.preferencesKey("columns")},on:{sorted:e.sorted},scopedSlots:e._u([{key:"cell-enabled",fn:function(t){return[t.row.enabled?r("div",{staticClass:"status-index-field select-none status-published"},[e._v("Enabled")]):r("div",{staticClass:"status-index-field select-none status-draft bg-red-100"},[e._v("Disabled")])]}},{key:"cell-source",fn:function(t){var i=t.row;return[r("a",{staticStyle:{"word-break":"break-all"},attrs:{href:e.cp_url("redirect/redirects/"+i.id)}},[e._v(e._s(i.source))])]}},{key:"actions",fn:function(t){var i=t.row;t.index;return[r("dropdown-list",[r("dropdown-item",{attrs:{text:e.__("Edit"),redirect:e.cp_url("redirect/redirects/"+i.id)}}),e._v(" "),r("dropdown-item",{staticClass:"warning",attrs:{text:e.__("Delete")},on:{click:function(t){e.$refs["deleter_"+i.id].confirm()}}},[r("resource-deleter",{ref:"deleter_"+i.id,attrs:{resource:i,reload:!0},on:{deleted:function(t){return e.removeRow(i.id)}}})],1)],1)]}}],null,!0)})],1),e._v(" "),r("data-list-pagination",{staticClass:"mt-3",attrs:{"resource-meta":e.meta,"per-page":e.perPage},on:{"page-selected":e.selectPage,"per-page-changed":e.changePerPage}})],1)}}],null,!1,3149455362)})],1)}),[],!1,null,null,null).exports;const f=a({mixins:[u,{data:function(){return{deletingRow:!1}},computed:{deletingModalTitle:function(){return this.deletingModalTitleFromRowKey("source")}},methods:{confirmDeleteRow:function(e,t){this.deletingRow={id:e,index:t}},deletingModalTitleFromRowKey:function(e){return __("Delete")+" "+this.items[this.deletingRow.index][e]},deleteRow:function(e,t){var r=this,i=this.deletingRow.id;t=t||__("Deleted"),this.$axios.delete(cp_url("".concat(e,"/").concat(i))).then((function(){var e=_.indexOf(r.items,_.findWhere(r.items,{id:i}));r.items.splice(e,1),r.deletingRow=!1,r.$toast.success(t),location.reload()})).catch((function(e){r.$toast.error(e.response?e.response.data.message:__("Something went wrong"))}))},cancelDeleteRow:function(){this.deletingRow=!1}}}],components:{Listing:u},data:function(){return{listingKey:"errors",preferencesPrefix:"redirect.errors",requestUrl:cp_url("redirect/api/errors"),columns:this.columns}},methods:{relativeDate:function(e){return moment.unix(e).fromNow()}}},(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",[e.initializing?r("div",{staticClass:"card loading"},[r("loading-graphic")],1):e._e(),e._v(" "),e.initializing?e._e():r("data-list",{ref:"datalist",attrs:{rows:e.items,columns:e.columns,sort:!1,"sort-column":e.sortColumn,"sort-direction":e.sortDirection},on:{"visible-columns-updated":function(t){e.visibleColumns=t}},scopedSlots:e._u([{key:"default",fn:function(t){t.hasSelections;return r("div",{},[r("div",{staticClass:"card overflow-hidden p-0 relative"},[r("div",{staticClass:"flex flex-wrap items-center justify-between px-2 pb-2 text-sm border-b"},[r("data-list-filter-presets",{ref:"presets",attrs:{"active-preset":e.activePreset,"active-preset-payload":e.activePresetPayload,"active-filters":e.activeFilters,"has-active-filters":e.hasActiveFilters,"preferences-prefix":e.preferencesPrefix,"search-query":e.searchQuery},on:{selected:e.selectPreset,reset:e.filtersReset}}),e._v(" "),r("div",{staticClass:"w-full flex-1"}),e._v(" "),r("div",{staticClass:"flex space-x-2 mt-2"},[r("button",{directives:[{name:"show",rawName:"v-show",value:e.isDirty,expression:"isDirty"}],staticClass:"btn btn-sm ml-2",domProps:{textContent:e._s(e.__("Reset"))},on:{click:function(t){return e.$refs.presets.refreshPreset()}}}),e._v(" "),r("button",{directives:[{name:"show",rawName:"v-show",value:e.isDirty,expression:"isDirty"}],staticClass:"btn btn-sm ml-2",domProps:{textContent:e._s(e.__("Save"))},on:{click:function(t){return e.$refs.presets.savePreset()}}}),e._v(" "),r("data-list-column-picker",{attrs:{"preferences-key":e.preferencesKey("columns")}})],1),e._v(" "),r("a",{staticClass:"ml-2 mt-2 btn btn-sm flex items-center",attrs:{href:e.cp_url("redirect/errors/clear")}},[e._v("Clear all errors")])],1),e._v(" "),r("div",[r("data-list-filters",{ref:"filters",attrs:{filters:e.filters,"active-preset":e.activePreset,"active-preset-payload":e.activePresetPayload,"active-filters":e.activeFilters,"active-filter-badges":e.activeFilterBadges,"active-count":e.activeFilterCount,"search-query":e.searchQuery,"is-searching":!0,"saves-presets":!0,"preferences-prefix":e.preferencesPrefix},on:{changed:e.filterChanged,saved:function(t){return e.$refs.presets.setPreset(t)},deleted:function(t){return e.$refs.presets.refreshPresets()}}})],1),e._v(" "),r("div",{directives:[{name:"show",rawName:"v-show",value:0===e.items.length,expression:"items.length === 0"}],staticClass:"p-6 text-center text-gray-500",domProps:{textContent:e._s(e.__("No results"))}}),e._v(" "),r("div",{staticClass:"overflow-x-auto overflow-y-hidden"},[r("data-list-table",{directives:[{name:"show",rawName:"v-show",value:e.items.length,expression:"items.length"}],attrs:{"allow-bulk-actions":!1,loading:e.loading,reorderable:!1,sortable:!0,"toggle-selection-on-row-click":!1,"allow-column-picker":!0,"column-preferences-key":e.preferencesKey("columns")},on:{sorted:e.sorted},scopedSlots:e._u([{key:"cell-url",fn:function(t){var i=t.row;return[r("a",{staticClass:"text-blue hover:text-blue-dark",staticStyle:{"word-break":"break-all"},attrs:{href:e.cp_url("redirect/errors/"+i.id)}},[e._v(e._s(i.url))])]}},{key:"cell-lastSeenAt",fn:function(t){var i=t.row;return[r("span",{domProps:{innerHTML:e._s(e.relativeDate(i.lastSeenAt))}})]}},{key:"cell-handled",fn:function(e){return[e.row.handled?r("div",{staticClass:"bg-green block h-3 w-2 mr-auto rounded-full"}):r("div",{staticClass:"bg-red block h-3 w-2 mr-auto rounded-full"})]}},{key:"cell-handledDestination",fn:function(t){var i=t.row;return[r("span",{staticStyle:{"word-break":"break-all"}},[e._v(e._s(i.handledDestination))])]}},{key:"actions",fn:function(t){var i=t.row;t.index;return[i.handled?e._e():r("a",{staticClass:"text-blue inline-block",attrs:{href:e.cp_url("redirect/redirects/create")+"?source="+encodeURI(i.url)}},[r("svg",{staticClass:"w-4 h-4 mr-2",attrs:{"aria-hidden":"true",focusable:"false","data-prefix":"far","data-icon":"plus",role:"img",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 384 512"}},[r("path",{attrs:{fill:"currentColor",d:"M368 224H224V80c0-8.84-7.16-16-16-16h-32c-8.84 0-16 7.16-16 16v144H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h144v144c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16V288h144c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z"}})])])]}}],null,!0)})],1)]),e._v(" "),r("data-list-pagination",{staticClass:"mt-3",attrs:{"resource-meta":e.meta,"per-page":e.perPage},on:{"page-selected":e.selectPage,"per-page-changed":e.changePerPage}})],1)}}],null,!1,2208292533)})],1)}),[],!1,null,null,null).exports;Statamic.$components.register("publish-form-redirect",o),Statamic.$components.register("errors-listing",f),Statamic.$components.register("redirect-listing",d)})()})();