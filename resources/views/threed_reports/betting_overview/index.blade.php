@extends('layouts.main')
@section('page_title', 'Three D Bettings Overview')
@section('threed_overview', 'active-link')
@section('3d-block', 'block')
@section('3d-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <threed-betting-overview-component />
    </div>
@endsection
