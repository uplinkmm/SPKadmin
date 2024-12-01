@extends('layouts.main')
@section('page_title', 'Three D')
@section('threed_reports.game_setting.index', 'active-link')
@section('3d-block', 'block')
@section('3d-collapse','data-twe-collapse-show')


@section('content')
    <div id="app">
        <game-setting-crud-component />
    </div>
@endsection
