@extends('layouts.main')
@section('page_title', 'Two D Bet List')
@section('twod_bet_list', 'active-link')
@section('2d-block', 'block')
@section('2d-collapse','data-twe-collapse-show')
@section('content')
    <div id="app">
        <twod-bet-list-component />
    </div>
@endsection
