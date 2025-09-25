@extends('layouts.main')
@section('page_title', 'draw results')
@section('draw_result_lists', 'active-link')
@section('draw-block', 'block')
@section('draw-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <draw-game-results  />
    </div>
@endsection
