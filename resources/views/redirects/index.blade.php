@extends('statamic::layout')
@section('title', 'Redirects')

@section('content')
    @unless($redirects->isEmpty())
        <div class="flex mb-3">
            <h1 class="flex-1">Redirects</h1>

            <a href="{{ cp_route('redirect.redirects.create') }}" class="btn-primary">Create</a>
        </div>

        <redirect-listing
            :initial-rows="{{ json_encode($redirects) }}"
            :columns="{{ json_encode($columns) }}"
            :endpoints="{}">
        ></redirect-listing>

    @else
        @include('statamic::partials.create-first', [
            'resource' => 'Redirect',
            'description' => 'Create a new redirect',
            'svg' => 'empty/collection',
            'route' => cp_route('redirect.redirects.create'),
        ])
    @endunless

@endsection
