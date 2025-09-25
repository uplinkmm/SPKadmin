@extends('layouts.main')
@section('page_title', 'draw promotions')
@section('draw_promotion_lists', 'active-link')
@section('draw-block', 'block')
@section('draw-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <draw-promotion-lists />
    </div>
@endsection
