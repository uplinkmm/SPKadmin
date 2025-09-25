@extends('layouts.main')
@section('page_title', 'draw user betting lists')
@section('draw_user_betting_lists', 'active-link')
@section('draw-block', 'block')
@section('draw-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <draw-user-betting-lists />
    </div>
@endsection
