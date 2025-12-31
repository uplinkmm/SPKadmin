@extends('layouts.main')
@section('page_title', 'Slot Transcations')
@section('buffalo-transcation', 'active-link')
@section('slot-block', 'block')
@section('slot-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <buffalo-transcations />
    </div>
@endsection
