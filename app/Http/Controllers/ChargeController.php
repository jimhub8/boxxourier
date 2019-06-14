<?php

namespace App\Http\Controllers;

use App\Charge;
use App\Shipment;
use Illuminate\Http\Request;
use Auth;

class ChargeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        
		// $this->Validate($request, [
		// 	'form.charges' => 'required',
		// 	'form.town_name' => 'required',
		// ]);
        // return $request->all();
        $charges = $request->form['charges'];
        $town = $request->schedule['town_name'];
        $vat = $charges * 0.16;
        // $request->schedule['id'];
        $total = ($charges * 0.16) + $charges;
        $charge = Charge::updateOrCreate(
            ['town' => $town],
            ['charge' => $charges, 'total' => $total, 'vat' => $vat, 'user_id' => Auth::id(), 'town_id' => $request->schedule['id']]
        );
        return $charge;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\vat  $charge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $charge = Charge::find($id);
        $charge->town = $request->schedule['town_name'];
        $charge->town_id = $request->schedule['id'];
        $charge->charge = $request->form['charge'];
        $charge->total = $request->form['total'];
        $charge->vat = $request->form['vat'];
        $charge->user_id = Auth::id();
        $charge->save();
        return $charge;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Charge  $charge
     * @return \Illuminate\Http\Response
     */
    public function destroy(Charge $charge)
    {
        //
    }

    public function shipCharge(Request $request, $id)
    {
        // return $request->all();
        $shipment = Shipment::find($id);
        if ($request->Stype == 'OVS') {
            $charges = $request->select['charges'];
            // return $charges->charge;
            foreach ($charges as $value) {
                $charge = $value['charge'];
            }
            // $vat = $charge * 0.16
                // return $charge;
            $shipment->charges = $charge;
        }elseif($request->Stype == 'dist') {
            $distance = $request->form['distance'];
            if ($distance <= 5) {
                $charge = 200;
                // $vat = $charge * 0.16
            } else {
                $charge = (($distance - 5) * 25) + 200;
                // $vat = $charge * 0.16
            }
            
            $shipment->charges = $charge;
        }
        // return $charge;
        $shipment->save();
        
        return $shipment;
    }

    public function getCharges()
    {
        return Charge::all();
    }
}
