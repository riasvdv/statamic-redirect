<div class="card p-0 overflow-hidden h-full">
    <div class="flex justify-between items-center p-4">
        <h2>
            <a href="{{ cp_route('redirect.index') }}" class="flex items-center">
                <div class="h-6 w-6 mr-2 text-gray-800">
                    @cp_svg('icons/light/git')
                </div>
                <span>{{ $title }}</span>
            </a>
        </h2>
    </div>
    <div>
        <div>
            <table data-size="sm" tabindex="0" class="data-table">
                <tbody tabindex="0">
                @foreach ($errors as $error)
                    <tr class="sortable-row outline-none" tabindex="0">
                        <td class="text-gray-800">
                            {{ $error->url }}
                        </td>
                        <td class="">
                            <span>{{ \Carbon\Carbon::createFromTimestamp($error->lastSeenAt)->diffForHumans() }}</span>
                        </td>
                        <th class="actions-column">
                            @if (! $error->handled)
                                <a href="{{ cp_route('redirect.redirects.create', ['source' => urlencode($error->url)]) }}" class="text-blue flex align-center mr-2">
                                    <svg
                                        class="w-4 h-4 mr-2"
                                        aria-hidden="true"
                                        focusable="false"
                                        data-prefix="far"
                                        data-icon="plus"
                                        role="img"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 384 512"
                                    >
                                        <path
                                                fill="currentColor"
                                                d="M368 224H224V80c0-8.84-7.16-16-16-16h-32c-8.84 0-16 7.16-16 16v144H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h144v144c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16V288h144c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z"
                                        ></path>
                                    </svg>
                                </a>
                            @endif
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
