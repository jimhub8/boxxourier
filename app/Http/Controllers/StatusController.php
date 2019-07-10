<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Shipment;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ShipmentStatus;

class StatusController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Status::orderBy('name', 'ASC')->get();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $status = new Status;
        $status->name = $request->name;
        $status->save();
        return $status;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return $request->all();
        $shipments = ShipmentStatus::where('shipment_id', $id)->get();
        $shipments->transform(function ($shipment) {
            $random_color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            $shipment->color = $random_color;
            return $shipment;
        });
        return response()->json($shipments);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Status $status)
    {
        // return $request->all();
        $status = Status::find($request->id);
        $status->name = $request->name;
        $status->save();
        return $status;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Status::find($id)->delete();
    }

    public function getStatuses()
    {
        // return Status::orderBy('name', 'ASC')->get();
        $user = Auth::user();
        if ($user->hasAllPermissions(['update delivered', 'update dispatched', 'update returned'])) {
            return Status::orderBy('name', 'ASC')->get();
        } elseif ($user->hasAllPermissions(['update delivered', 'update dispatched'])) {
            return Status::where('name', '!=', 'Returned')->orderBy('name', 'ASC')->get();
        } elseif ($user->hasAllPermissions(['update delivered', 'update returned'])) {
            return Status::where('name', '!=', 'Dispatched')->orderBy('name', 'ASC')->get();
        } elseif ($user->hasAllPermissions(['update dispatched', 'update returned'])) {
            return Status::where('name', '!=', 'Delivered')->orderBy('name', 'ASC')->get();
        } elseif ($user->hasAllPermissions('update delivered')) {
            return Status::where('name', '!=', 'Returned')->Where('name', '!=', 'Dispatched')->orderBy('name', 'ASC')->get();
        } elseif ($user->hasAllPermissions('update returned')) {
            return Status::where('name', '!=', 'Delivered')->where('name', '!=', 'Dispatched')->orderBy('name', 'ASC')->get();
        } elseif ($user->hasAllPermissions('update dispatched')) {
            return Status::where('name', '!=', 'Delivered')->where('name', '!=', 'Returned')->orderBy('name', 'ASC')->get();
        } else {
            return Status::where('name', '!=', 'Delivered')->where('name', '!=', 'Returned')->where('name', '!=', 'Dispatched')->orderBy('name', 'ASC')->get();
        }
    }

    public function getStat()
    {
        return Status::orderBy('name', 'ASC')->get();
        // return response()->json(Status::orderBy('name', 'ASC')->get(), 200);
        // return response()->json(array(Status::orderBy('name', 'ASC')->get())), 200);
        // return response()->json(array('success' => 'Log In Successful'), 200);
    }

    public function scheduled()
    {
        $date1 = Carbon::today();
        $date2 = new Carbon('tomorrow');
        $shipment = Shipment::setEagerLoads([])->where('status', 'Scheduled')->whereBetween('derivery_date', [$date1, $date2])->take(500)->get();
        return $shipment;
    }

    public function getScheduled(Request $request)
    {
        // return $request->all();
        $print_shipment = Shipment::where('status', 'Scheduled')->whereBetween('derivery_date', [$request->start_date, $request->end_date])->where('printed', 0)->take(500)->latest()->get();
        $id = [];
        foreach ($print_shipment as $selectedItems) {
            $id[] = $selectedItems['id'];
        }
        // $status = $request->form['status'];
        // $derivery_time = $request->form['derivery_time'];
        // $remark = $request->form['remark'];
        // $derivery_date = $request->form['scheduled_date'];
        $shipment = Shipment::whereIn('id', $id)->update(['printed' => 1, 'printReceipt' => 1]);
        return $print_shipment;
    }
    public function getStickers(Request $request)
    {
        // return $request->all();
        $branch = Branch::select('id')->where('branch_name', 'Client')->first();
        // return $branch;
        $sticker_shipment = Shipment::where('status', 'Scheduled')->whereBetween('derivery_date', [$request->start_date, $request->end_date])->where('branch_id', $branch->id)->where('sticker', 0)->where('country_id', Auth::user()->country_id)->take(500)->latest()->get();
        $id = [];
        foreach ($sticker_shipment as $selectedItems) {
            $id[] = $selectedItems['id'];
        }
        // $shipment = Shipment::whereIn('id', $id)->update(['sticker' => 1]);
        return $sticker_shipment;
    }

    public function getDeriveredA()
    {
        return Shipment::where('status', 'Delivered')->latest()->take(500)->get();
    }

    public function customerS(Request $request)
    {

    }
    // public function getDelStatuses()
    // {
    //     return DelStatus::orderBy('name', 'ASC')->get();
    // }
}
