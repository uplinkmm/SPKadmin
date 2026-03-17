@extends('layouts.main')
@section('page_title', request('type') === 'promotion' ? 'Promotion Lists' : 'Ads Lists')

@section('content')
    <div id="app">
        <ads list-type="{{ request('type', 'ads') }}" />
    </div>
@endsection
