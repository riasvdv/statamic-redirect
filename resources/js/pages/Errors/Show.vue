<script setup>
import { ref } from 'vue'
import { Head, Link } from '@statamic/cms/inertia';
import { Header, Button, Panel, PanelHeader, Heading, Card, Table, TableColumns, TableColumn, TableRows, TableRow, TableCell } from "@statamic/cms/ui";

defineProps({
  title: { type: String, required: true },
  createUrl: { type: String, required: true },
  deleteUrl: { type: String, required: true },
  error: {
    id: Number,
    url: String,
    handled: Boolean,
    handledDestination: String,
    lastSeenAt: Number,
    hitsCount: Number,
    hits: Array,
  },
});
</script>

<template>
  <Head :title="[title, __('Redirect')]" />

  <Header :title="__('Error details')" icon="link">
    <div class="flex gap-2">
      <Link :href="deleteUrl" class="mr-2">
        <Button variant="danger">Delete error</Button>
      </Link>
      <Link v-if="!error.handled" :href="createUrl">
        <Button variant="primary">Create redirect</Button>
      </Link>
    </div>
  </Header>

  <Panel>
    <Panel-header class="flex items-center justify-between min-h-10">
      <Heading><p class="ml-1 inline-block truncate w-128" :title="error.url">{{ error.url }}</p></Heading>
    </Panel-header>
    <Card>
      <Table>
        <TableColumns>
          <TableColumn><span>User agent</span></TableColumn>
          <TableColumn><span>IP</span></TableColumn>
          <TableColumn><span>Referer</span></TableColumn>
          <TableColumn><span>Time</span></TableColumn>
        </TableColumns>
        <TableRows v-if="error.hits.length > 0">
          <TableRow v-for="hit in error.hits">
            <TableCell><p class="m-0 truncate" style="max-width: 40vw" :title="hit.data.userAgent">{{ hit.data.userAgent }}</p></TableCell>
            <TableCell>{{ hit.data.ip }}</TableCell>
            <TableCell>{{ hit.data.referer }}</TableCell>
            <TableCell>
              <time datetime="{{ new Date(hit.timestamp).toISOString() }}">
                {{ hit.timestampForHumans }}
              </time>
            </TableCell>
          </TableRow>
        </TableRows>
        <TableRows v-else>
          <TableRow class="row outline-none" tabindex="0">
            <TableCell colspan="4">No hits found.</TableCell>
          </TableRow>
        </TableRows>
      </Table>
    </Card>
  </Panel>
</template>
