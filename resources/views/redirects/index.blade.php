@extends('statamic::layout')
@section('title', 'Redirects')

@section('content')
    <div class="flex mb-3">
        <h1 class="flex-1">Redirects</h1>

        <a href="{{ cp_route('redirect.redirects.create') }}" class="btn-primary">Create</a>
    </div>

    <redirect-listing :filters="{{ $filters->toJson() }}"></redirect-listing>
@endsection
