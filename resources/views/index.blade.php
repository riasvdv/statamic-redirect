@extends("statamic::layout")
@section("title", Statamic::crumb("Redirect"))

@section('content')
    <ui-header title="{{ __('Redirect dashboard') }}" icon="arrow-up-right">
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
        <div class="grid grid-cols-3 gap-8 mb-8">
            {!! app(\Rias\StatamicRedirect\Widgets\ErrorsLastMonthWidget::class)->html() !!}
            {!! app(\Rias\StatamicRedirect\Widgets\ErrorsLastWeekWidget::class)->html() !!}
            {!! app(\Rias\StatamicRedirect\Widgets\ErrorsLastDayWidget::class)->html() !!}
        </div>
    @endif
    {!! app(\Rias\StatamicRedirect\Widgets\ErrorsWidget::class)->html() !!}

    <x-statamic::docs-callout
        topic="Statamic Redirect"
        url="https://statamic.com/addons/rias/redirect"
    />
@endsection
