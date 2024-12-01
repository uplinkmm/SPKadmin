@extends('layouts.main')
@section('page_title', 'Balance Transactions')
@section('balance_transactions.index', 'active-link')

@section('content')
    <div id="app">
        <balance-transaction-list-component />
    </div>
@endsection
