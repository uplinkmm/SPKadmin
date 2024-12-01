<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
        <meta name="csrf-token" content="{{ csrf_token() }}"/>

	<link rel="preconnect" href="https://fonts.gstatic.com">

	<title> @yield('page_title') </title>
    @vite('resources/js/app.js')
    <link rel="stylesheet" href="{{asset('fontawesome/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/normalize.css')}}">
    <link rel="stylesheet" href="{{asset('css/style_web.css')}}">

    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js"></script>

    <script src="{{asset('js/jquery.js')}}"></script>
</head>

<body>
    <script type="module">
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-analytics.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional

        const firebaseConfig = {
            apiKey: "{{ env('GOOGLE_API_KEY') }}",
            authDomain: "{{ env('GOOGLE_AUTH_DOMAIN') }}",
            projectId: "{{ env('GOOGLE_PROJECT_ID') }}",
            storageBucket: "{{ env('GOOGLE_STORAGE_BUCKET') }}",
            messagingSenderId: "{{ env('GOOGLE_MESSAGING_SENDER_ID') }}",
            appId: "{{ env('GOOGLE_APP_ID') }}",
            measurementId: "{{ env('GOOGLE_MEASUREMENT_ID') }}"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
    </script>
	@yield('body-content')


    <script>
        $(document).ready(function(){
            $('#toggleBtn').on('click', function() {
                $('#sidebar').toggleClass('!w-0');
                $('#toggleBtn i').toggleClass('rotate-180');
                console.log('testing sidenav');
                $('#content_collapse').toggleClass('!ml-0 w-content');
            });
        });
  </script>
</body>

@yield('script_index')

</html>
