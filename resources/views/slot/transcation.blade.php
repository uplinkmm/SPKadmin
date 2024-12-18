@extends('layouts.main')
@section('page_title', 'Slot Transcations')
@section('slot_list', 'active-link')
@section('slot-block', 'block')
@section('slot-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <slot-transcation />
    </div>
@endsection
