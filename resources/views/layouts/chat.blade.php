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
        <style>
                .chat {
                  list-style: none;
                  margin: 0;
                  padding: 0;
                }
              
                .chat li {
                  margin-bottom: 10px;
                  padding-bottom: 5px;
                  border-bottom: 1px dotted #B3A9A9;
                }
              
                .chat li .chat-body p {
                  margin: 0;
                  color: #777777;
                }
              
                .panel-body {
                  overflow-y: scroll;
                  height: 350px;
                }
              
                ::-webkit-scrollbar-track {
                  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
                  background-color: #F5F5F5;
                }
              
                ::-webkit-scrollbar {
                  width: 12px;
                  background-color: #F5F5F5;
                }
              
                ::-webkit-scrollbar-thumb {
                  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
                  background-color: #555;
                }
              </style>
        {{-- @include('reports::app') --}}
    <div id="app">
        <main class="py-4">

            @yield('content')
        </main>
    </div>

<script src="{{ asset('vuetify/js/vuetify.js') }}"></script>
</body>
</html>
