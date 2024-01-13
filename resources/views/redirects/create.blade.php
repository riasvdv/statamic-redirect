@extends('statamic::layout')

@section('title', 'Create redirect')
@section('wrapper_class', 'max-w-3xl')

@section('content')
    <redirect-publish-form
        title="{{ __('Create redirect') }}"
        action="{{ cp_route('redirect.redirects.store') }}"
        :is-creating="true"
        :blueprint='@json($blueprint)'
        :initial-meta='@json($meta)'
        :initial-values='@json($values)'
        listing-url="{{ cp_route('redirect.redirects.index') }}"
        create-another-url="{{ cp_route('redirect.redirects.create') }}"
        :breadcrumbs="[
            {
                url: '{{ cp_route('redirect.redirects.index') }}',
                text: '{{ __('Redirects') }}'
            }
        ]"
    ></redirect-publish-form>
@stop
