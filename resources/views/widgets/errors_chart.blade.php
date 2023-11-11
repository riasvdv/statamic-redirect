<div class="card p-4 content">
    <div class="flex justify-between items-center">
        <h2>
            <a href="{{ cp_route('redirect.index') }}" class="flex items-center">
                <div class="h-6 w-6 mr-2 text-gray-800">
                    @cp_svg('icons/light/charts')
                </div>
                <span>{{ $title }}</span>
            </a>
        </h2>
    </div>
    <div class="px-2 py-2">
        @include('redirect::components.lineChart', ['data' => $data])
    </div>
</div>
