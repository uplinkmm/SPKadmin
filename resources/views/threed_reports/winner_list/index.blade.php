@extends('layouts.main')
@section('page_title', 'Three D')
@section('threed_winning_number', 'active-link')
@section('3d-block', 'block')
@section('3d-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <threed-winner-list-component />
    </div>
@endsection
