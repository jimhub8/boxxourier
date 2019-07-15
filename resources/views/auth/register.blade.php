@extends('layouts.app')

@section('style')
<style>
        .bubble-left:before {
            content: '';
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-top: 16px solid white;
            position: absolute;
            bottom: -13px;
            margin-left: 6px;
        }
        ceo-image {
            width: 55px;
            height: 55px;
            background-image: url(/storage/web/zuri.jpg);
            background-size: cover;
        }
@media only screen and (max-width: 768px) {
.layout .flex img{
margin-left: 60px !important;
}
}
</style>
@endsection
@section('content')

<template>
    <v-app id="inspire">
        <v-layout row wrap style="height: 100vh;">
            <v-flex sm7 style="background: url(/storage/signup-background.jpeg);background-attachment: fixed;">
                    {{--  <h2><strong style="color: #fff; margin: auto">Get your products in your customers hands faster</strong></h2>  --}}
                    <div class="container" style="margin: auto;width: 85%;">

                    <div class="font-weight-bold font-size-46 line-height-56" style="line-height: 3.5rem;font-size: 2.875rem;color: #fff;">
                        Get your products in<br>customers hands faster.
                      </div>
                      <div style="opacity: .85;color: #f0f0f0;font-weight: 600; font-size: 26px;">We store your items securely, then pick, pack and ship your orders on the same day.</div>

                      <div class="flex-1 row align-items-end" style="align-items: flex-end !important;margin-top: 45vh;">
                          <div class="col-lg-7 col-xl-5">
                              <div class="card bubble-left" style="border-radius: 10px;padding: 4px;">
                                  <div class="card-body text-body" style=" font-weight: 500; font: italic; ">"Boxleo Courier & Fulfillment Services provides
                                        us with a quality express courier services, with
                                        products reaching our customers in plenty of
                                        time, while offering great value for money. I would
                                        recommend them to anyone."

                                  </div>
                                </div> <div class="d-flex"><div class="mr-20 rounded-circle ceo-image">
                                    </div> <div style="flex-full: height; justify-content: center;">
                                        <div style=";color: #f6f6f6">
                                            <img src="{{ asset('storage/web/zuri.jpg') }}" alt="" style="width: 12%; border-radius: 50%;">
                                            <p style="margin: -40px 0 0 50px;">LILLY MACHARIA,<br><span style="font-size: 14; opacity: .85;">ZURI KIDS</span> </p></div></div></div></div></div>
                    </div>
            </v-flex>
            <v-flex sm4 offset-sm1 style="margin: auto;">
                <img src="{{ asset('/storage/logo/boxleo_logo.png') }}" alt="" srcset="">
                <hr>
                <strong>Welcome back!</strong>
                <p style="margin: 1.875rem 0;font-weight: 600;color: #8695af !important;">We''re looking forward to showing you around and getting to know you better.</p>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12">
                        <label for="name" class="ol-form-label text-md-right">{{ __('Full name') }}</label>
                        <input id="name" type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                        <label for="email" class="ol-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                        <label for="password" class="col-form-label text-md-right">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                                <label for="password-confirm" class="col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                <input id="password-confirm" type="password" class="form-control @error('password') is-invalid @enderror" name="password-confirm" required autocomplete="current-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                    </div>

                    {{--  <div class="form-group row">
                        <div class="col-md-6">
                        <label for="password-confirm" class="col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                        <input id="password-confirm" type="password" class="form-control @error('password') is-invalid @enderror" name="password-confirm" required autocomplete="current-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>  --}}


                    {{--  <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>  --}}

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember" style="margin-left: 15px">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>

                        </div>
                    </div>
                </form>

                <p style="margin-top: 13vh;">By creating your account, you are agreeing to our <a href="/#/terms">terms & conditions </a></p>

                <p class="text-center">Have an account? <a href="/login">Log in now</a></p>
            </v-flex>
        </v-layout>
    </v-app>
</template>
@endsection

