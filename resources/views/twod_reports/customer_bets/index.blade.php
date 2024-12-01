@extends('layouts.main')
@section('page_title', 'Two D Reports')
@section('twod_Report', 'active-link')
@section('2d-block', 'block')
@section('2d-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <twod-reports-list-component />
    </div>
@endsection
