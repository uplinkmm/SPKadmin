

@extends('layouts.main')
@section('page_title', 'Referral Promotions')
@section('referral_promotions', 'active-link')
@section('promotions-block', 'block')
@section('promotions-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <referral-promotions />
    </div>
@endsection
