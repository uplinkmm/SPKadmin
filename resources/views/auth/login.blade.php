@extends('layouts.master')
@section('page_title', 'Login')

@section('body-content')
<div id="app">
        @php
            $userType = request()->routeIs('agent_login') ? 'agent' : (request()->routeIs('login') ? 'admin' : null);
        @endphp
        <login-component :user-type="'{{ $userType }}'"/>
    </div>
@endsection
