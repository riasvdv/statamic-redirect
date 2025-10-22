<script setup>
import { ref } from 'vue'
import { Head, Form } from '@statamic/cms/inertia';
import { Header, Button, Panel, PanelHeader, Heading, Card, DocsCallout } from "@statamic/cms/ui";
import ErrorsListing from "../../components/Listing/ErrorsListing.vue";

defineProps({
  filters: { type: Array, required: true },
  clearUrl: { type: String, required: true },
  actionUrl: { type: String, required: true },
});

let refreshKey = ref(0);

function handleFinish() {
  refreshKey.value++;
}
</script>

<template>
  <Head :title="[__('Errors'), __('Redirect')]" />

  <Header :title="__('Errors')" icon="arrow-up-right">
    <Form
        :action="clearUrl"
        method="POST"
        @finish="handleFinish"
    >
      <Button type="submit" variant="primary">{{ __('Clear all errors') }}</Button>
    </Form>
  </Header>

  <Panel>
    <PanelHeader class="flex items-center justify-between min-h-10">
      <Heading>{{ __('Errors') }}</Heading>
    </PanelHeader>

    <Card>
      <ErrorsListing
          :key="refreshKey"
          :filters="filters"
          :action-url="actionUrl"
      />
    </Card>
  </Panel>

  <DocsCallout
      topic="Statamic Redirect"
      url="https://statamic.com/addons/rias/redirect"
  />
</template>
