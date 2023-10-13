@extends('statamic::layout')

@section('title', 'Create redirect')
@section('wrapper_class', 'max-w-3xl')

@section('content')
    <publish-form-redirect
        title="Create redirect"
        action="{{ cp_route('redirect.redirects.store') }}"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
        :breadcrumbs="[
            {
                url: '{{ cp_route('redirect.redirects.index') }}',
                text: '< Redirects'
            }
        ]"
        redirect-to="{{ request('source') ? cp_route('redirect.index') : cp_route('redirect.redirects.index') }}"
    ></publish-form-redirect>
@stop
