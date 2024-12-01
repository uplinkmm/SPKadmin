@extends('layouts.main')
@section('page_title', 'Topup Transactions')
@section('topup_transactions', 'active-link')

@section('content')
    <div id="app">
        <topup-transaction-list-component />
    </div>
@endsection
