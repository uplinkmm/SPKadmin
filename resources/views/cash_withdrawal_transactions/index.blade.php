@extends('layouts.main')
@section('page_title', 'Cash Withdrawal Transactions')
@section('withdrawal_transactions', 'active-link')

@section('content')
    <div id="app">
        <cash-withdrawal-transaction-list-component />
    </div>
@endsection
