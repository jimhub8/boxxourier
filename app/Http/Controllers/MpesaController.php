<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SmoDav\Mpesa\Laravel\Facades\Registrar;
use SmoDav\Mpesa\Laravel\Facades\STK;

class MpesaController extends Controller
{
    public function mpesa()
    {
        // $conf = 'http://courierS.dev/mpesa/confirm?secret=MVMcOQi4uAOQH73s';
        // $val = 'http://courierS.dev/mpesa/validate?secret=MVMcOQi4uAOQH73s';

        // $response = Registrar::register(600000)
        //     ->onConfirmation($conf)
        //     ->onValidation($val)
        //     ->submit();
        $response = STK::push(10, 254722000000, 'some reference', 'Test Payment');

        /****** OR ********/
        // $response = Registrar::submit(600000, $conf, $val);
    }
}
