@extends('layouts.main')
@section('page_title', 'Payment Provider')
@section('payment_providers', 'active-link')

@section('content')
    <div id="app">
        <payment-provider-crud-component />
    </div>
@endsection
