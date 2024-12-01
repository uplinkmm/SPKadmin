@extends('layouts.main')
@section('page_title', 'Three D Winning Number List')
@section('threed_winning_numbers', 'active-link')
@section('3d-block', 'block')
@section('3d-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <threed-winning-number-crud-component />
    </div>
@endsection
