@extends('layouts.main')
@section('page_title', 'Two D Close List')
@section('twod_close_list', 'active-link')
@section('2d-block', 'block')
@section('2d-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <twod-closing-list-component />
    </div>
@endsection
