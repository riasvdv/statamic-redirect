@extends('statamic::layout')

@section('title', __('Edit redirect'))
@section('wrapper_class', 'max-w-full')

@section('content')
    <redirect-publish-form
        title="{{ __('Update redirect') }}"
        action="{{ cp_route('redirect.redirects.update', $redirect->id()) }}"
        :is-creating="false"
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
