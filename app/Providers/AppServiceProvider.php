<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
// use App/Call;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // \App\Call::creating(function ($call) {
        //     // $call = new Call;
        //     // dd($call);
        //     $call->user_id = Auth::id();
        //     $call->shipment_id = $call->id;
        //     // $call->save();
        // });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
