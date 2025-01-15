@extends('layouts.main')
@section('page_title', 'Slot games')
@section('slot_game_lists', 'active-link')
@section('slot-block', 'block')
@section('slot-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <slot-game-lists />
    </div>
@endsection
