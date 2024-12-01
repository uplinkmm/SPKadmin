@extends('layouts.main')
@section('page_title', 'Agent Wallets')
@section('agent_wallets', 'active-link')
@section('agent-block', 'block')
@section('agent-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <agent-wallets />
    </div>
@endsection
