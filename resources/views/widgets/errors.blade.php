<ui-widget icon="link" title="{{ $title }}">
    <template #actions>
        <a href="{{ cp_route('redirect.errors.index') }}">
            <ui-button>View all</ui-button>
        </a>
        @can('create', \Rias\StatamicRedirect\Contracts\Redirect::class)
            <a href="{{ cp_route('redirect.redirects.create') }}">
                <ui-button variant="primary">Create redirect</ui-button>
            </a>
        @endcan
    </template>
    @if(! count($errors))
        <ui-description class="flex-1 flex items-center justify-center">
            {{ __('Good job! No 404 errors.') }}
        </ui-description>
    @else
        <div class="px-4 py-3">
            <ui-table class="w-full">
                <ui-table-columns>
                    <ui-table-column>Path</ui-table-column>
                    <ui-table-column>Latest error</ui-table-column>
                    @can('create', \Rias\StatamicRedirect\Contracts\Redirect::class)
                        <ui-table-column></ui-table-column>
                    @endcan
                </ui-table-columns>
                <ui-table-rows>
                    @foreach ($errors as $error)
                        <ui-table-row>
                            <ui-table-cell>
                                <div class="flex items-center">
                                    <div class="size-2 rounded-full mr-2 {{ $error->handled ? 'bg-green-600' : 'bg-red-500' }}"></div>
                                        <a class="" href="{{ cp_route('redirect.errors.show', $error->id) }}" style="word-break: break-all">{{ $error->url }}</a>
                                    </div>
                            </ui-table-cell>
                            <ui-table-cell>
                                {{ \Carbon\Carbon::createFromTimestamp($error->lastSeenAt)->diffForHumans() }}
                            </ui-table-cell>
                            <ui-table-cell>
                                @can('create', \Rias\StatamicRedirect\Contracts\Redirect::class)
                                    @if (! $error->handled)
                                        <a href="{{ cp_route('redirect.redirects.create', ['source' => $error->url]) }}" class="text-blue flex align-center">
                                            <ui-button icon="plus" size="xs" title="Create redirect"></ui-button>
                                        </a>
                                    @endif
                                @endcan
                            </ui-table-cell>
                        </ui-table-row>
                    @endforeach
                </ui-table-rows>
            </ui-table>
        </div>
    @endif
</ui-widget>
