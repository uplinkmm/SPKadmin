@extends('layouts.main')
@section('page_title', 'Agent Commissions')
@section('agents_commission', 'active-link')
@section('agent-block', 'block')
@section('agent-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <agents-commission />
    </div>
@endsection
