@extends('statamic::layout')
@section('title', 'Redirects')

@section('content')

    <header class="mb-3">
        <h1>Redirect</h1>
    </header>

    <div class="card p-2 content mb-2">
        <div class="flex flex-wrap -mx-2 mb-4">
            <div class="w-1/3 px-2">
                <p class="mb-4">errors in the last month</p>
                <div class="px-1">
                    @include('redirect::components.lineChart', ['data' => $notFoundMonth])
                </div>
            </div>
            <div class="w-1/3 px-2">
                <p class="mb-4">errors in the last week</p>
                <div class="px-1">
                    @include('redirect::components.lineChart', ['data' => $notFoundWeek])
                </div>
            </div>
            <div class="w-1/3 px-2">
                <p class="mb-4">errors in the last day</p>
                <div class="px-1">
                    @include('redirect::components.lineChart', ['data' => $notFoundDay])
                </div>
            </div>
        </div>
    </div>

    <errors-listing :filters="{{ $filters->toJson() }}"></errors-listing>

    @include('statamic::partials.docs-callout', [
        'topic' => 'Statamic Redirect',
        'url' => 'https://statamic.com/addons/rias/redirect'
    ])

@endsection
