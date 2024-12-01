@extends('layouts.main')
@section('page_title', 'Three D')
@section('threed_customer_bets', 'active-link')
@section('3d-block', 'block')
@section('3d-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <threed-customer-bets-component />
    </div>
@endsection
