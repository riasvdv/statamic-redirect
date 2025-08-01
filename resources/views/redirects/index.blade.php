@extends('statamic::layout')
@section('title', Statamic::crumb("Redirect", "Redirects"))

@section('content')
    <redirect-listing
        :columns="{{ json_encode($columns) }}"
        :filters="{{ json_encode($filters) }}"
        action-url="{{ $actionUrl }}"
    ></redirect-listing>
@endsection
