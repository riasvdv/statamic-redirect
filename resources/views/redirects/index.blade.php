@extends('statamic::layout')
@section('title', Statamic::crumb("Redirect", "Redirects"))

@section('content')
    <ui-header title="{{ __('Redirects') }}" icon="arrow-up-right">

        @can('create', \Rias\StatamicRedirect\Contracts\Redirect::class)
            <ui-dropdown>
                <ui-dropdown-menu>
                    <ui-dropdown-item
                        icon="upload"
                        text="Import CSV"
                        href="{{ cp_route('redirect.redirects.import') }}"
                    ></ui-dropdown-item>
                    <ui-dropdown-item
                        icon="download"
                        text="Export as CSV"
                        href="{{ cp_route('redirect.export', ['type' => 'csv']) }}?download=true"
                    ></ui-dropdown-item>
                    <ui-dropdown-item
                        icon="download"
                        text="Export as JSON"
                        href="{{ cp_route('redirect.export', ['type' => 'json']) }}?download=true"
                    ></ui-dropdown-item>
                </ui-dropdown-menu>
            </ui-dropdown>
            <ui-button
                href="{{ cp_route('redirect.redirects.create') }}"
                text="{{ __('Create Redirect') }}"
                variant="primary"
            />
        @endcan
    </ui-header>

    <redirect-listing
        :columns="{{ json_encode($columns) }}"
        :filters="{{ json_encode($filters) }}"
        action-url="{{ $actionUrl }}"
    ></redirect-listing>
@endsection
