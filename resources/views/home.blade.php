@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Home</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                            <a href="/login" class="btn btn-primary ">Login</a>
                        </div>
                    @endif
                    Welcome {{ Auth::user()->name }} to our app
                        <br>
                <a href="/courier" class="btn btn-primary">Go to the app</a>
                </div>
            </div>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum commodi qui perferendis ea praesentium enim, vero, nobis illo nihil tenetur eaque dolorum eos officiis! Suscipit unde impedit sequi accusamus debitis?
        </div>
    </div>
</div>
@endsection