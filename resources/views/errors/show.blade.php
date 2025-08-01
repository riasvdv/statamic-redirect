@extends('statamic::layout')

@section('title', 'Error details - ' . $error->url)
@section('wrapper_class', 'max-w-3xl')

@section('content')
    <ui-header title="Error details" icon="link">
        <div class="flex gap-2">
            <a href="{{ cp_route('redirect.errors.delete', $error->id) }}" class="mr-2">
                <ui-button variant="danger">Delete error</ui-button>
            </a>
            @if(! $error->handled)
                <a href="{{ cp_route('redirect.redirects.create', ['source' => $error->url]) }}">
                    <ui-button variant="primary">Create redirect</ui-button>
                </a>
            @endif
        </div>
    </ui-header>

    <ui-panel>
        <ui-panel-header class="flex items-center justify-between min-h-10">
            <ui-heading><p class="ml-1 inline-block truncate w-128" title="{{ $error->url }}">{{ $error->url }}</p></ui-heading>
        </ui-panel-header>
        <ui-card>
            <ui-table>
                <ui-table-columns>
                    <ui-table-column><span>User agent</span></ui-table-column>
                    <ui-table-column><span>IP</span></ui-table-column>
                    <ui-table-column><span>Referer</span></ui-table-column>
                    <ui-table-column><span>Time</span></ui-table-column>
                </ui-table-columns>
                <ui-table-rows>
                    @forelse ($error->hits->reverse() as $hit)
                        <ui-table-row>
                            <ui-table-cell><p class="m-0 truncate w-64" title="{{ $hit->data['userAgent'] ?? '' }}">{{ $hit->data['userAgent'] ?? '' }}</p></ui-table-cell>
                            <ui-table-cell>{{ $hit->data['ip'] ?? '' }}</ui-table-cell>
                            <ui-table-cell>{{ $hit->data['referer'] ?? '' }}</ui-table-cell>
                            <ui-table-cell><time datetime="{{ \Illuminate\Support\Carbon::createFromTimestamp($hit->timestamp)->toIso8601String() }}">{!! isset($hit->timestamp) ? str_replace(' ', '&nbsp;', \Illuminate\Support\Carbon::createFromTimestamp($hit['timestamp'])->diffForHumans()) : '' !!}</time></ui-table-cell>
                        </ui-table-row>
                    @empty
                        <ui-table-row class="row outline-none" tabindex="0">
                            <ui-table-cell colspan="4">No hits found.</ui-table-cell>
                        </ui-table-row>
                    @endforelse
                </ui-table-rows>
            </ui-table>
        </ui-card>
    </ui-panel>
@stop
