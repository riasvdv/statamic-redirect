@extends('statamic::layout')

@section('title', 'Error details - ' . $error->url)
@section('wrapper_class', 'max-w-3xl')

@section('content')
    <div class="page-wrapper max-w-3xl">
        <div>
            <div class="flex items-center mb-3">
                <h1 class="flex-1 flex items-center">Error details for <p class="ml-1 inline-block truncate w-128"
                                                                          title="{{ $error->url }}">{{ $error->url }}</p>
                </h1>
                <a href="{{ cp_route('redirect.errors.delete', $error->id) }}" class="mr-1 btn">Delete error</a>
                <a href="{{ cp_route('redirect.redirects.create', ['source' => $error->url]) }}" class="btn-primary">Create redirect</a>
            </div>
            <div class="card p-0 relative">
                <table tabindex="0" class="data-table">
                    <thead>
                    <tr>
                        <th class="sortable-column"><span>User agent</span></th>
                        <th class="sortable-column"><span>IP</span></th>
                        <th class="sortable-column"><span>Referer</span></th>
                        <th class="sortable-column"><span>Time</span></th>
                    </tr>
                    </thead>
                    <tbody tabindex="0">
                        @forelse ($error->hits->reverse() as $hit)
                            <tr class="row outline-none" tabindex="0">
                                <td><p class="m-0 truncate w-64" title="{{ $hit->data['userAgent'] ?? '' }}">{{ $hit->data['userAgent'] ?? '' }}</p></td>
                                <td>{{ $hit->data['ip'] ?? '' }}</td>
                                <td>{{ $hit->data['referer'] ?? '' }}</td>
                                <td><time datetime="{{ \Illuminate\Support\Carbon::createFromTimestamp($hit->timestamp)->toIso8601String() }}">{!! isset($hit->timestamp) ? str_replace(' ', '&nbsp;', \Illuminate\Support\Carbon::createFromTimestamp($hit['timestamp'])->diffForHumans()) : '' !!}</time></td>
                            </tr>
                        @empty
                            <tr class="row outline-none" tabindex="0">
                                <td colspan="4">No hits found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
