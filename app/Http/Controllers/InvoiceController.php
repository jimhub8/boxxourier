<?php

namespace App\Http\Controllers;

use App\User;
use App\Mail\InvoiceMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Shipment;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $shipment = Shipment::with(['products'])->where('id', 5)->get();
        // $shipment->transform(function($ship) {

        // });
        $data = array(
            'shipment' => $shipment,
            // 'user' => $user,
            // 'pdf' => base64_decode($pdf->output()),
        );
        return view('emails.verifyEmailFirst', compact('shipment'));
    }
    public function invoice(Request $request, $id)
    {
        // return $request->all();
        $type = $request->type;
        $shipment = Shipment::with(['products'])->where('id', $id)->get();
        // $user = [];
        $user = Auth::user();
        // dd($cart);
        // return view('admin.invoice', compact('shipment', 'cart'));
        // dd($cart);
        // dd(base64_decode($pdf->output()));
        $data = array(
            'shipment' => $shipment,
            'user' => $user,
            // 'pdf' => base64_decode($pdf->output()),
        );

        // dd($data);
        // Mail::to('jimkiarie8@gmail.com')->queue(new InvoiceMail($data, $shipment));
        $pdf = app('dompdf.wrapper')->loadView('emails.invoice', ['shipment' => $shipment]);
        if ($type == 'stream') {
            return $pdf->stream('invoice.pdf');
        }

        if ($type == 'download') {
            return $pdf->download('invoice.pdf');
        }
        // return 'success you are a geneus';
        // return view('emails.invoice', compact('data'));

    }
    public function invoiceOrder()
    {
        $shipment = Shipment::where('id', 1)->get();
        return view('emails.invoice', compact('shipment'));
    }

    public function filterInvoice(Request $request)
    {
        $this->Validate($request, [
            'client' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        // return $request->all();
        $date_array = array(
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        );
        if (Auth::user()->hasRole('Client')) {
            if ($request->client) {
                $shipments = Shipment::whereBetween('created_at', $date_array)->get();
                // $shipments->transform(function ($shipment) {
                //     // $shipment->client = Auth::user();
                //     return $shipment;
                // });
                return array('shipment' => $shipments, 'client' => Auth::user());
                // return $shipments;
            }
        } else {
            // if ($request->client) {
            $shipments = Shipment::whereBetween('created_at', $date_array)->get();
            // $shipments->transform(function ($shipment) use ($request) {
            //     $shipment->client = User::find($request->client);
            //     return $shipment;
            // });
            $client = User::find($request->client);
            return array('shipment' => $shipments, 'client' => $client);
            // return $shipments;
            // } else {
            //     $shipments = Shipment::whereBetween('created_at', $date_array)->take('20')->get();
            //     return $shipments;
            // }
        }
    }
}
