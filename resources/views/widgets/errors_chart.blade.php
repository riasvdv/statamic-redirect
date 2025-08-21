<ui-widget icon="pulse" title="{{ $title }}">
    <div class="p-6">
        @include('redirect::components.lineChart', ['data' => $data])
    </div>
</ui-widget>
