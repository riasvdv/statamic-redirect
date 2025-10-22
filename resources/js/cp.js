import RedirectListing from "./components/Listing/RedirectListing.vue";
import ErrorsListing from "./components/Listing/ErrorsListing.vue";
import Dashboard from "./pages/Dashboard.vue";
import RedirectsIndex from "./pages/Redirects/Index.vue";
import RedirectsEmpty from "./pages/Redirects/Empty.vue";
import RedirectsPublish from "./pages/Redirects/Publish.vue";
import RedirectsImport from "./pages/Redirects/Import.vue";
import ErrorsIndex from "./pages/Errors/Index.vue";
import ErrorsShow from "./pages/Errors/Show.vue";

Statamic.booting(() => {
    Statamic.$inertia.register('redirect::Dashboard', Dashboard);
    Statamic.$inertia.register('redirect::Redirects/Index', RedirectsIndex);
    Statamic.$inertia.register('redirect::Redirects/Empty', RedirectsEmpty);
    Statamic.$inertia.register('redirect::Redirects/Publish', RedirectsPublish);
    Statamic.$inertia.register('redirect::Redirects/Import', RedirectsImport);

    Statamic.$inertia.register('redirect::Errors/Index', ErrorsIndex);
    Statamic.$inertia.register('redirect::Errors/Show', ErrorsShow);

    Statamic.$components.register("redirect-listing", RedirectListing);
    Statamic.$components.register("errors-listing", ErrorsListing);
});
