@extends('layouts.main')
@section('page_title', 'Transition')
<!-- @section('game_transitions', 'active-link') -->
@section('agent-block', 'block')
@section('agent-collapse','data-twe-collapse-show')
@if($game_type == '2D')
    @section('game_transitions2D', 'active-link')
@elseif($game_type == '3D')
    @section('game_transitions3D', 'active-link')
@endif
@section('content')
    <div id="app">
        <game-transitions  :game-type="'{{ $game_type }}'"/>
    </div>
@endsection
