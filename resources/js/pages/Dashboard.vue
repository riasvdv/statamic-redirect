<script setup>
import { Head } from '@statamic/cms/inertia';
import { Header, Description, DocsCallout } from '@statamic/cms/ui';
import DynamicHtmlRenderer from '../components/DynamicHtmlRenderer.vue';

defineProps({
  enabled: Boolean,
  logHits: Boolean,
  cleanupHasRun: Boolean,
  cleanupLastRanAtHuman: String,
  widgets: {
    errorsLastMonth: String,
    errorsLastWeek: String,
    errorsLastDay: String,
    errors: String,
    topErrors: String,
  },
});
</script>

<template>
  <Head :title="[__('Redirect dashboard')]" />

  <Header :title="__('Redirect dashboard')" icon="arrow-up-right"/>

  <Description v-show="!enabled" class="bg-yellow py-2 px-4 content mb-2 text-center">
    Redirect is currently <strong>disabled</strong>. Change the <code>statamic.redirect.enable</code> config value to <code>true</code> to enable redirects & logging.
  </Description>

  <Description v-show="!cleanupHasRun" class="bg-yellow py-2 px-4 content mb-2 text-center">
    Error cleanup has not ran for <strong>{{ cleanupLastRanAtHuman }}</strong>.<br>It should be running every day, make sure you run your
    <a class="text-blue" href="https://laravel.com/docs/scheduling#running-the-scheduler" target="_blank">Laravel schedule</a>.
  </Description>

  <div v-show="logHits" class="grid grid-cols-3 gap-8 mb-8">
    <DynamicHtmlRenderer :html="widgets.errorsLastMonth"/>
    <DynamicHtmlRenderer :html="widgets.errorsLastWeek"/>
    <DynamicHtmlRenderer :html="widgets.errorsLastDay"/>
  </div>

  <div class="grid grid-cols-2 gap-8 mb-8" style="grid-template-columns: 1fr 1fr;">
    <DynamicHtmlRenderer :html="widgets.errors"/>
    <DynamicHtmlRenderer :html="widgets.topErrors"/>
  </div>

  <DocsCallout
      topic="Statamic Redirect"
      url="https://statamic.com/addons/rias/redirect"
  />
</template>
