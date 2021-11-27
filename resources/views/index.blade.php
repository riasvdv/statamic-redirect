@extends('statamic::layout')
@section('title', 'Redirects')

@section('content')

    <header class="mb-3">
        <h1>Redirect</h1>
    </header>

    @if(! config('statamic.redirect.enable', true))
        <div class="card bg-yellow py-2 px-4 leading-loose content mb-2 text-center">
            Redirect is currently <strong>disabled</strong>. Change the <code>statamic.redirect.enable</code> config value to <code>true</code> to enable redirects & logging.
        </div>
    @endif

    @if(config('statamic.redirect.log_hits'))
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
    @endif

    <errors-listing :filters="{{ $filters->toJson() }}"></errors-listing>

    @include('statamic::partials.docs-callout', [
        'topic' => 'Statamic Redirect',
        'url' => 'https://statamic.com/addons/rias/redirect'
    ])

@endsection
