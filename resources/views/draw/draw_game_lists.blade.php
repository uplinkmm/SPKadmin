@extends('layouts.main')
@section('page_title', 'draw games')
@section('draw_game_lists', 'active-link')
@section('draw-block', 'block')
@section('draw-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <draw-game-lists />
    </div>
@endsection
