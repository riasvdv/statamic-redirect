<ui-widget icon="pulse" title="{{ $title }}">
    <div class="p-8">
        @include('redirect::components.lineChart', ['data' => $data])
    </div>
</ui-widget>
