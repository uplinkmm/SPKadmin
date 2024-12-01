@extends('layouts.main')
@section('page_title', 'Agent List')
@section('agent_lists', 'active-link')
@section('agent-block', 'block')
@section('agent-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <agent-lists />
    </div>
@endsection
