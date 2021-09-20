@extends('statamic::layout')

@section('title', 'Import redirects')
@section('wrapper_class', 'max-w-3xl')

@section('content')
    <form action="{{ cp_route('redirect.redirects.handleImport') }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}

        <header class="mb-3">
            <div class="flex items-center justify-between">
                <h1>Redirects Import</h1>
                <button class="btn-primary">Import</button>
            </div>
        </header>

        <div class="card rounded p-3 lg:px-7 lg:py-5 shadow bg-white">
            <header class="text-center mb-6">
                <h1 class="mb-3">Start import</h1>
                <p class="text-grey">Import redirects by providing a CSV file with <code>source</code>, <code>destination</code>, <code>type</code> and <code>match_type</code> columns. </p>
            </header>
            <div class="mb-5">
                <label for="file" class="font-bold text-base mb-sm">File</label>
                <input id="file" name="file" type="file" tabindex="1" class="input-text" accept="text/csv">
                <div class="text-2xs text-grey-60 mt-1 flex items-center">
                    A CSV file, make sure it includes a header row
                </div>
            </div>
            <div class="mb-5">
                <label for="delimiter" class="font-bold text-base mb-sm">Delimiter</label>
                <input id="delimiter" name="delimiter" placeholder="," value="," type="text" tabindex="1" class="input-text">
                <div class="text-2xs text-grey-60 mt-1 flex items-center">
                    Defaults to <code>,</code>. Is usually one of <code>,</code>,<code>;</code>,<code>|</code>
                </div>
            </div>
        </div>
    </form>
@endsection
