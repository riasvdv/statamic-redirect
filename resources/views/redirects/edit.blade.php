@extends('statamic::layout')

@section('title', 'Edit redirect')
@section('wrapper_class', 'max-w-3xl')

@section('content')
    <publish-form-redirect
        title="Update redirect"
        action="{{ cp_route('redirect.redirects.update', $redirect->id()) }}"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
        :breadcrumbs="[
            {
                url: '{{ cp_route('redirect.redirects.index') }}',
                text: '< Redirects'
            }
        ]"
        redirect-to="{{ cp_route('redirect.redirects.edit', $redirect->id()) }}"
    ></publish-form-redirect>
@stop
