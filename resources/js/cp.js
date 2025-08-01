import RedirectListing from "./components/Listing/RedirectListing.vue";
import ErrorsListing from "./components/Listing/ErrorsListing.vue";

Statamic.booting(() => {
    Statamic.$components.register("redirect-listing", RedirectListing);
    Statamic.$components.register("errors-listing", ErrorsListing);
});
