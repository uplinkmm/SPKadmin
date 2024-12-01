@extends('layouts.main')
@section('page_title', 'Users')
@section('agents_users', 'active-link')
@section('agent-block', 'block')
@section('agent-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <agents-users />
    </div>
@endsection
