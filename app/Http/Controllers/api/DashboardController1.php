<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Shipment;

class DashboardController1 extends Controller
{
    public function customerShip(Request $request)
    {
        if ($request->search) {
        $bar_code = $request->search;
            return Shipment::where('client_id', Auth::id())->where('bar_code', 'LIKE', "%{$bar_code}%")->paginate(10);
        } else {
            return Shipment::where('client_id', Auth::id())->paginate(10);
        }

    }
    public function getsearchRe(Request $request)
    {
        // return $request->all();
        $bar_code = $request->search;
		return $barcode = Shipment::where('bar_code', 'LIKE', "%{$bar_code}%")->where('client_id', Auth::id())->paginate(10);
        // return Shipment::where('bar_code', $request->all())->paginate(10);
    }
    public function customerCount()
    {
        return Shipment::where('client_id', Auth::id())->count();
    }
    public function customerScheduled()
    {
        return Shipment::where('client_id', Auth::id())->where('status', 'Scheduled')->count();
    }
    public function customerDelivered()
    {
        return Shipment::where('client_id', Auth::id())->where('status', 'Delivered')->count();
    }
    public function customerCanceled()
    {
        return Shipment::where('client_id', Auth::id())->where('status', 'Cancelled')->count();
    }
    public function delayedCount()
    {
        return Shipment::where('client_id', Auth::id())->where('status', 'Delayed')->count();
    }

    // Charts
    public function getClientShip()
    {
        $shipments = DB::table('shipments')
            ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
            ->orderBy('id', 'asc')
            ->groupBy('date')
            ->where('client_id', Auth::id())
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

    public function getClientScheduled()
    {
		// return Shipment::take(100)->get();
        $shipments = DB::table('shipments')
            ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
            ->orderBy('id', 'asc')
            ->groupBy('date')
            ->where('client_id', Auth::id())
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

    public function getClientDelivered()
    {
		// return Shipment::take(100)->get();
        $shipments = DB::table('shipments')
            ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
            ->orderBy('id', 'asc')
            ->groupBy('date')
            ->where('client_id', Auth::id())
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

    public function getClientCancled()
    {
		// return Shipment::take(100)->get();
        $shipments = DB::table('shipments')
            ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
            ->orderBy('id', 'asc')
            ->groupBy('date')
            ->where('client_id', Auth::id())
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
