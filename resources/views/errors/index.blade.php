@extends("statamic::layout")
@section("title", Statamic::crumb("Redirect"))

@section('content')
    <ui-header title="{{ __('Errors') }}" icon="arrow-up-right">
        <form method="POST" action="{{ cp_route('redirect.errors.clear') }}">
            @csrf
            <ui-button text="{{ __('Clear all errors') }}" type="submit" variant="primary" />
        </form>
    </ui-header>

    <ui-panel>
        <ui-panel-header class="flex items-center justify-between min-h-10">
            <ui-heading>{{ __('Errors') }}</ui-heading>
        </ui-panel-header>

        <ui-card>
            <errors-listing
                :filters="{{ $filters->toJson() }}"
                action-url="{{ $actionUrl }}"
            ></errors-listing>
        </ui-card>
    </ui-panel>

    <x-statamic::docs-callout
        topic="Statamic Redirect"
        url="https://statamic.com/addons/rias/redirect"
    />
@endsection
