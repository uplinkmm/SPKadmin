@extends('layouts.main')
@section('page_title', 'Promotions')
@section('promotions', 'active-link')
@section('promotions-block', 'block')
@section('promotions-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <promotions />
    </div>
@endsection
