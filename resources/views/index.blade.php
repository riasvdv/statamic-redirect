@extends("statamic::layout")
@section("title", Statamic::crumb("Redirect"))

@section('content')
    <ui-header title="{{ __('Redirect') }}" icon="arrow-up-right">
    </ui-header>

    @if(! config('statamic.redirect.enable', true))
        <ui-description class="bg-yellow py-2 px-4 content mb-2 text-center">
            Redirect is currently <strong>disabled</strong>. Change the <code>statamic.redirect.enable</code> config value to <code>true</code> to enable redirects & logging.
        </ui-description>
    @endif

    @if((! $cleanupLastRanAt || \Illuminate\Support\Carbon::createFromTimestamp($cleanupLastRanAt) < now()->subDays(2)) && app()->environment() !== 'local')
        <ui-description class="bg-yellow py-2 px-4 content mb-2 text-center">
            Error cleanup has not ran for <strong>{{ $cleanupLastRanAt ? \Illuminate\Support\Carbon::createFromTimestamp($cleanupLastRanAt)->diffForHumans(syntax: 1) : '2 days' }}</strong>.<br>It should be running every day, make sure you run your
            <a class="text-blue" href="https://laravel.com/docs/8.x/scheduling#running-the-scheduler" target="_blank">Laravel schedule</a>.
        </ui-description>
    @endif

    @if(config('statamic.redirect.log_hits'))
        <ui-card-panel heading="Statistics">
            <div class="grid grid-cols-3 gap-8">
                <div class="pb-4">
                    <ui-subheading size="lg" class="mb-4">Last month</ui-subheading>
                    <div class="px-2">
                        @include('redirect::components.lineChart', ['data' => $notFoundMonth])
                    </div>
                </div>
                <div class="pb-4">
                    <ui-subheading size="lg" class="mb-4">Last week</ui-subheading>
                    <div class="px-2">
                        @include('redirect::components.lineChart', ['data' => $notFoundWeek])
                    </div>
                </div>
                <div class="pb-4">
                    <ui-subheading size="lg" class="mb-4">Last day</ui-subheading>
                    <div class="px-2">
                        @include('redirect::components.lineChart', ['data' => $notFoundDay])
                    </div>
                </div>
            </div>
        </ui-card-panel>
    @endif

    <ui-panel>
        <ui-panel-header class="flex items-center justify-between min-h-10">
            <ui-heading>{{ __('Errors') }}</ui-heading>
            <div class="flex gap-2">
                <form method="POST" action="{{ cp_route('redirect.errors.clear') }}">
                    @csrf
                    <ui-button text="{{ __('Clear all errors') }}" type="submit" size="sm" />
                </form>
            </div>
        </ui-panel-header>

        <ui-card>
            <errors-listing
                :filters="{{ $filters->toJson() }}"
                action-url="{{ $actionUrl }}"
            ></errors-listing>
        </ui-card>
    </ui-panel>

    <x-statamic::docs-callout
        topic="Statamic Redirect"
        url="https://statamic.com/addons/rias/redirect"
    />
@endsection
