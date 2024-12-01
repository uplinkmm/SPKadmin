@extends('layouts.main')
@section('page_title', 'User Limit')
@section('limit_user', 'active-link')
@section('user-block', 'block')
@section('user-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <limit-user-component />
    </div>
@endsection
