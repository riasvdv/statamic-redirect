@extends('statamic::layout')
@section('title', 'Redirects')

@section('content')
    <redirect-listing
        :can-create="{{ \Statamic\Facades\User::fromUser(auth()->user())->isSuper() || \Statamic\Facades\User::fromUser(auth()->user())->hasPermission('create redirects') ? 'true' : 'false' }}"
        create-url="{{ cp_route('redirect.redirects.create') }}"
        create-label="Create redirect"
        :columns="{{ $columns->toJson() }}"
        :filters="{{ $filters->toJson() }}"
    />
@endsection
