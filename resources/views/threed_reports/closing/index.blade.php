@extends('layouts.main')
@section('page_title', 'Three D Close List')
@section('threed_close_list', 'active-link')
@section('3d-block', 'block')
@section('3d-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <threed-closing-list-component />
    </div>
@endsection
