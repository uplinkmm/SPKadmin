@extends('layouts.main')
@section('page_title', 'New User Bonus')
@section('new_user_bonus', 'active-link')
@section('promotions-block', 'block')
@section('promotions-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <new-user-bonus />
    </div>
@endsection
