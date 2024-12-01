@extends('layouts.main')
@section('page_title', 'Two D Bettings Overview')
@section('twod_betting_overview', 'active-link')
@section('2d-block', 'block')
@section('2d-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <twod-bettings-overview-component />
    </div>
@endsection
