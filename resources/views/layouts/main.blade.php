@extends('layouts.master')

@section('body-content')
    <div id="app" class="main-container ">
        @include('layouts.sidebar')

        <div id="content_collapse" class="main-content ml-60"  style="transition: margin 0.3s;">
            @include('layouts.navbar')

            <main class="inner-container bg-[#f0f1f700] py-3">
                @yield('content')
            </main>
        </div>

    </div>
@endsection
