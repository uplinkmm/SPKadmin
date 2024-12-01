@extends('layouts.main')
@section('page_title', 'Two D Betting Amounts')
@section('twod_reports.betting_amounts.index', 'active-link')
@section('2d-block', 'block')
@section('2d-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <twod-betting-amounts-component />
    </div>
@endsection
