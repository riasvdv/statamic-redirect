@extends('statamic::layout')

@section('title', 'Import redirects')
@section('content-card-modifiers', 'bg-architectural-lines')

@section('content')
    <form class="max-w-3xl mx-auto" action="{{ cp_route('redirect.redirects.handleImport') }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}

        <ui-header title="{{ __('Redirects import') }}" icon="upload">
            <ui-button type="submit" variant="primary">Import</ui-button>
        </ui-header>

        <ui-card-panel heading="Start import" subheading="Import redirects by providing a CSV file with <code>source</code>, <code>destination</code>, <code>type</code> and <code>match_type</code> columns. <br>Optionally you can add a <code>site</code> column as well.">
            <div class="space-y-8">
                <ui-field label="File" instructions="A CSV file, make sure it includes a header row" required="true">
                    <ui-input id="file" name="file" type="file" tabindex="1" accept="text/csv" />
                </ui-field>

                <ui-field label="Delimiter" instructions="Defaults to <code>,</code>. Is usually one of <code>,</code>,<code>;</code>,<code>|</code>" required="true">
                    <ui-input id="delimiter" name="delimiter" placeholder="," value="," type="text" tabindex="1" />
                </ui-field>
            </div>
        </ui-card-panel>
    </form>
@endsection
