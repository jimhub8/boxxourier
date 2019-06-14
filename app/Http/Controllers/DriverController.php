<?php

namespace App\Http\Controllers;

use App\Shipment;
use Illuminate\Http\Request;
use Auth;
use DB;
class DriverController extends Controller
{
    public function DriverShip()
    {
        return Shipment::where('driver', Auth::id())->paginate(10);
    }
    
    public function driverCount()
    {
        return Shipment::where('driver', Auth::id())->count();
    }
    public function driverScheduled()
    {
        return Shipment::where('driver', Auth::id())->where('status', 'Scheduled')->count();
    }
    public function driverDelivered()
    {
        return Shipment::where('driver', Auth::id())->where('status', 'Delivered')->count();
    }
    public function driverCanceled()
    {
        return Shipment::where('driver', Auth::id())->where('status', 'Cancelled')->count();
    }
    public function delayedCount()
    {
        return Shipment::where('driver', Auth::id())->where('status', 'Delayed')->count();
    }

    // Charts
    public function getRinderShip()
    {
        $shipments = DB::table('shipments')
			->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
			->orderBy('id', 'asc')
			->groupBy('date')
            ->where('driver', Auth::id())
			->get();
        $lables = [];
        $rows = [];
		foreach ($shipments as $key => $shipment) {
            $lables[] = $shipment->date;
			$rows[] = $shipment->count;
        }
        $data = array(
            'lables' => $lables, 
            'rows' => $rows
        );
        return response()->json(['data' => $data]);
    }

    public function getRinderScheduled()
    {
		// return Shipment::take(100)->get();
        $shipments = DB::table('shipments')
            ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
            ->orderBy('id', 'asc')
            ->groupBy('date')
            ->where('driver', Auth::id())
            ->where('status', 'Scheduled')
            ->get();

        $lables = [];
        $rows = [];
        foreach ($shipments as $key => $shipment) {
            $lables[] = $shipment->date;
            $rows[] = $shipment->count;
        }
        $data = array(
            'lables' => $lables,
            'rows' => $rows
        );
        return response()->json(['data' => $data]);
    }

    public function getRinderDelivered()
    {
		// return Shipment::take(100)->get();
        $shipments = DB::table('shipments')
            ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
            ->orderBy('id', 'asc')
            ->groupBy('date')
            ->where('driver', Auth::id())
            ->where('status', 'Delivered')
            ->get();

        $lables = [];
        $rows = [];
        foreach ($shipments as $key => $shipment) {
            $lables[] = $shipment->date;
            $rows[] = $shipment->count;
        }
        $data = array(
            'lables' => $lables,
            'rows' => $rows
        );
        return response()->json(['data' => $data]);
    }

    public function getRinderCancled()
    {
		// return Shipment::take(100)->get();
        $shipments = DB::table('shipments')
            ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
            ->orderBy('id', 'asc')
            ->groupBy('date')
            ->where('driver', Auth::id())
            ->where('status', 'Cancelled')
            ->get();

        $lables = [];
        $rows = [];
        foreach ($shipments as $key => $shipment) {
            $lables[] = $shipment->date;
            $rows[] = $shipment->count;
        }
        $data = array(
            'lables' => $lables,
            'rows' => $rows
        );
        return response()->json(['data' => $data]);
    }

}
