@extends('statamic::layout')
@section('title', Statamic::crumb('Redirect', 'Redirects'))
@section('content-card-modifiers', 'bg-architectural-lines')

@section('content')
    <header class="py-8 mt-8 text-center">
        <h1 class="text-[25px] font-medium antialiased flex justify-center items-center gap-3">
            <ui-icon name="arrow-up-right" class="size-5 text-gray-500"></ui-icon>
            Redirects
        </h1>
    </header>

    <ui-empty-state-menu heading="A redirect on a website is a way to automatically send visitors from one URL to another. It's commonly used when a page has been moved, deleted, or replaced, ensuring users and search engines reach the correct location.">
        <ui-empty-state-item
            href="{{ cp_route('redirect.redirects.create') }}"
            icon="arrow-up-right"
            heading="{{ __('Create Redirect') }}"
            description="{{ __('Get started by creating your first redirect.') }}"
        ></ui-empty-state-item>
    </ui-empty-state-menu>

    <x-statamic::docs-callout
        topic="Statamic Redirect"
        url="https://statamic.com/addons/rias/redirect"
    />
@endsection
