@extends('layouts.main')
@section('page_title', 'Two D Winner List')
@section('twod_winner', 'active-link')
@section('2d-block', '!block')
@section('2d-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <twod-winner-list-component />
    </div>
@endsection
