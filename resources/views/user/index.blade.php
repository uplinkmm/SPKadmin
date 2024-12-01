@extends('layouts.main')
@section('page_title', 'User List')
@section('user_list', 'active-link')
@section('user-block', 'block')
@section('user-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <user-list-component />
    </div>
@endsection
