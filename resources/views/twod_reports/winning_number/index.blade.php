@extends('layouts.main')
@section('page_title', 'Two D Winning Number List')
@section('twod_winning_number', 'active-link')
@section('2d-block', 'block')
@section('2d-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <twod-winning-number-crud-component />
    </div>
@endsection
