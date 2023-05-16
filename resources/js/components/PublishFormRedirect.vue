<template>
  <div id="statamic-redirect">
    <publish-form
      :title="title"
      :action="action"
      :blueprint="blueprint"
      :meta="meta"
      :values="values"
      @saved="saved"
    ></publish-form>
  </div>
</template>

<style>
  #statamic-redirect .publish-tab-actions {
    display: none;
  }
</style>

<script>
export default {
  props: {
    title: String,
    action: String,
    blueprint: Array,
    meta: Array,
    redirectTo: String,
    values: Array,
  },
  methods: {
    saved(event) {
      setTimeout(() => this.redirect(event.data), 600);
    },
    redirect(slug) {
      window.location.href = this.createEditRoute(slug);
    },
    createEditRoute(slug) {
      return this.redirectTo.replace("x-redirect", slug);
    },
  },
};
</script>
