@extends('layouts.main')
@section('page_title', 'Slot Users')
@section('slot_user_lists', 'active-link')
@section('slot-block', 'block')
@section('slot-collapse','data-twe-collapse-show')

@section('content')
    <div id="app">
        <slot-user-lists />
    </div>
@endsection
