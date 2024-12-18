@extends('layouts.main')
@section('page_title', 'User Report')
@section('user_report', 'active-link')
@section('slot-block', 'block')
@section('slot-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <user-report />
    </div>
@endsection
