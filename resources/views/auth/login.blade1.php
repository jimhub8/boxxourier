@extends('layouts.app') 
@section('content')

<template>
<v-app id="inspire">
    <div id="login" style="margin-top: -120px;">
    <v-content>
        <v-container fluid fill-height style="background: url('/storage/ps/track.jpg'); margin-top: 200px; background-position: center; background-attachment: fixed; background-size: cover;height: 100vh;">
            <v-layout align-center justify-center>
                <v-flex xs12 sm8 md4>
                <v-flex xs12 sm12>
                    {{-- <v-img style="width: 100px; height: 100px; marge"
                        src="{{ asset('storage/logo-white.png') }}"
                        gradient="to top right, rgba(100,115,201,.33), rgba(25,32,72,.7)"
                    ></v-img> --}}
                    <img src="{{ asset('/storage/Boxleo logo_Approved-01.jpg') }}" alt="Boxleo"  style="width: 150px; margin-top: -150px;
                    margin-left: 150px;">
                    </v-flex>
                    <v-card class="elevation-12">
                        <v-toolbar dark color="primary">
                            <v-toolbar-title>Login form</v-toolbar-title>
                            <v-spacer></v-spacer>
                            {{-- <v-tooltip bottom>
                                <v-btn slot="activator" icon large target="_blank">
                                    <i class="fab fa-google" style="font-size: 20px;"></i>
                                </v-btn>
                                <span>Google</span>
                            </v-tooltip>
                            <v-tooltip right>
                                <v-btn slot="activator" icon large href="/facebook" target="_blank">
                                    <i class="fab fa-facebook" style="font-size: 20px;"></i>
                                </v-btn>
                                <span>Facebook</span>
                            </v-tooltip> --}}
                        </v-toolbar>
                        <v-form method="POST" action="{{ route('login') }}">
                            <v-card-text>
                                    @csrf
                                    <v-text-field prepend-icon="person" name="email" label="Email" type="text"></v-text-field>
                                    @if ($errors->has('email'))
                                    <p style="color: red;">{{ $errors->first('email') }}</p>
                                    {{-- <span class="invalid-feedback">
                                        <strong>email</strong>
                                    </span>  --}}
                                    @endif
                                    <v-divider></v-divider>
                                    <v-text-field id="password" prepend-icon="lock" name="password" label="Password" type="password"></v-text-field>

                                    @if ($errors->has('password'))
                                    {{-- <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>  --}}
                                    <p style="color: red;">{{ $errors->first('password') }}</p>
                                    @endif

                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                            </v-card-text>
                            <v-card-actions>
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    </div>
                                </div>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" type="submit">Login</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </v-content>
</div>
</v-app>
</template>
@endsection