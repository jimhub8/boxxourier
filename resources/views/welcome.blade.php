@extends('layouts.app') 
@section('title') Courier
@endsection
 
@section('content')
<my-header :user="{{ json_encode($auth_user) }}"></my-header>
{{--
<my-header :user="{{ Auth::user() }}" :role="{{ json_encode($rolename) }}"></my-header> --}}
<transition name="fade">
    <router-view :user="{{ json_encode($auth_user) }}"></router-view>
    {{--
    <router-view :user="{{ Auth::user() }}" :role="{{ json_encode($rolename) }}"></router-view> --}}
</transition>
@endsection