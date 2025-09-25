@extends('layouts.main')
@section('page_title', 'draw winner lists')
@section('draw_winner_lists', 'active-link')
@section('draw-block', 'block')
@section('draw-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <draw-winner-lists />
    </div>
@endsection
