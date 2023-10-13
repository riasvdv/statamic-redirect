@extends('statamic::layout')
@section('title', 'Redirects')

@section('content')
    <redirect-listing
        :can-create="{{ auth()->user()->isSuper() || auth()->user()->hasPermission('create redirects') ? 'true' : 'false' }}"
        create-url="{{ cp_route('redirect.redirects.create') }}"
        create-label="Create redirect"
        :columns="{{ $columns->toJson() }}"
        :filters="{{ $filters->toJson() }}"
    >
        <template slot="twirldown">
            @if(auth()->user()->isSuper() || auth()->user()->hasPermission('create redirects'))
                <dropdown-item :text="__('Import CSV')" redirect="{{ cp_route('redirect.redirects.import') }}"></dropdown-item>
            @endif
            <dropdown-item :text="__('Export as CSV')" redirect="{{ cp_route('redirect.export', ['type' => 'csv']) }}?download=true"></dropdown-item>
            <dropdown-item :text="__('Export as JSON')" redirect="{{ cp_route('redirect.export', ['type' => 'json']) }}?download=true"></dropdown-item>
        </template>
    </redirect-listing>
@endsection
