<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user" content="Auth::user()">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="/storage/Boxleo logo_Approved-01.jpg">

    {{-- <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase.js"></script> --}}
    {{-- <script>
      // Initialize Firebase
      var config = {
        apiKey: "AIzaSyD6ZwYtv9R4LJusjTLCVBtdbZn9nxj4xe0",
        authDomain: "courier-55c5e.firebaseapp.com",
        databaseURL: "https://courier-55c5e.firebaseio.com",
        projectId: "courier-55c5e",
        storageBucket: "",
        messagingSenderId: "380640669657"
      };
      firebase.initializeApp(config);
    </script> --}}
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- <script src="{{ asset('js/jquery.js') }}"></script> --}}

    <!-- Fonts -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    {{-- {{--<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css"> --}}
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
     <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNzKeF6ZwxlAOUCyeH8UxvvYRHP_w_Guk&libraries=places"></script> --}}
 {{--     <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    <script src="https://unpkg.com/vue-chartjs/dist/vue-chartjs.min.js"></script>
   <script src="https://unpkg.com/chart.js@2.7.2/dist/Chart.bundle.js"></script>
    <script src="https://unpkg.com/vue-chartkick@0.3.1"></script> --}}
      {{-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNzKeF6ZwxlAOUCyeH8UxvvYRHP_w_Guk&callback=initMap"
  type="text/javascript"></script>--}}
    <!-- Styles -->

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vueDash.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('vuetify/css/vuetify.min.css') }}" rel="stylesheet"> --}}
</head>
<body>
        {{-- @include('reports::app') --}}
    <div id="app">
        <main class="py-4">

            @yield('content')
        </main>
    </div>

<script src="{{ asset('vuetify/js/vuetify.js') }}"></script>
</body>
</html>
