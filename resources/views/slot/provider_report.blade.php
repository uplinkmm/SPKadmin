@extends('layouts.main')
@section('page_title', 'Provider Report')
@section('provider_report', 'active-link')
@section('slot-block', 'block')
@section('slot-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <provider-report />
    </div>
@endsection
