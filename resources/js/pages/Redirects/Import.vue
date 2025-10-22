<script setup>
import { Head, Form } from '@statamic/cms/inertia';
import { Header, Button, CardPanel, Field, Input } from '@statamic/cms/ui';

defineProps({
  importUrl: { type: String, required: true },
})
</script>

// @todo: How do I add bg-architectural-lines
<template>
  <Head :title="[__('Redirects import'), __('Redirect')]" />

  <Form class="max-w-3xl mx-auto" :action="importUrl" method="POST" enctype="multipart/form-data">
    <Header :title="__('Redirects import')" icon="upload">
      <Button type="submit" variant="primary">Import</Button>
    </Header>

    <CardPanel heading="Start import" subheading="Import redirects by providing a CSV file with <code>source</code>, <code>destination</code>, <code>type</code> and <code>match_type</code> columns. <br>Optionally you can add a <code>site</code> column as well.">
      <div class="space-y-8">
        <Field label="File" instructions="A CSV file, make sure it includes a header row" required="true">
          <Input id="file" name="file" type="file" tabindex="1" accept="text/csv" />
        </Field>

        <Field label="Delimiter" instructions="Defaults to <code>,</code>. Is usually one of <code>,</code>,<code>;</code>,<code>|</code>" required="true">
          <Input id="delimiter" name="delimiter" placeholder="," value="," type="text" tabindex="1" />
        </Field>
      </div>
    </CardPanel>
  </Form>
</template>
