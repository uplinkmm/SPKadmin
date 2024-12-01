@extends('layouts.main')
@section('page_title', 'Agent Withdrawals')
@section('agents_transcations_status', 'active-link')
@section('agent-block', 'block')
@section('agent-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <agents-transcations-status />
    </div>
@endsection
