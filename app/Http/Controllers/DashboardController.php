<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Shipment;
use Auth;
use DB;
use App\Country;

class DashboardController extends Controller
{
    public function getChartData(Request $request)
    {
		// return Shipment::take(100)->get();
        if (empty($request->id)) {
            $shipments = DB::table('shipments')
                ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
                ->orderBy('id', 'asc')
                ->groupBy('date')
                ->where('country_id', Auth::user()->country_id)
                ->get();
        } else {
            $shipments = DB::table('shipments')
                ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
                ->orderBy('id', 'asc')
                ->groupBy('date')
                ->where('country_id', $request->id)
                ->get();
        }
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

    public function getChartScheduled(Request $request)
    {
		// return Shipment::take(100)->get();
        if (empty($request->id)) {
            $shipments = DB::table('shipments')
                ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
                ->orderBy('id', 'asc')
                ->groupBy('date')
                ->where('country_id', Auth::user()->country_id)
                ->where('status', 'Scheduled')
                ->get();
        } else {
            $shipments = DB::table('shipments')
                ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
                ->orderBy('id', 'asc')
                ->groupBy('date')
                ->where('country_id', $request->id)
                ->where('status', 'Scheduled')
                ->get();
        }

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

    public function getChartDelivered(Request $request)
    {
		// return Shipment::take(100)->get();
        if (empty($request->id)) {
            $shipments = DB::table('shipments')
                ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
                ->orderBy('id', 'asc')
                ->groupBy('date')
                ->where('country_id', Auth::user()->country_id)
                ->where('status', 'Delivered')
                ->get();
        } else {
            $shipments = DB::table('shipments')
                ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
                ->orderBy('id', 'asc')
                ->groupBy('date')
                ->where('country_id', $request->id)
                ->where('status', 'Delivered')
                ->get();
        }

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

    public function getChartCancled(Request $request)
    {
		// return Shipment::take(100)->get();
        if (empty($request->id)) {
            $shipments = DB::table('shipments')
                ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
                ->orderBy('id', 'asc')
                ->groupBy('date')
                ->where('country_id', Auth::user()->country_id)
                ->where('status', 'Cancelled')
                ->get();
        } else {
            $shipments = DB::table('shipments')
                ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
                ->orderBy('id', 'asc')
                ->groupBy('date')
                ->where('country_id', $request->id)
                ->where('status', 'Cancelled')
                ->get();
        }

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

    public function getCountCount(Request $request)
    {
        $countries = ['name' => 'Kenya', 'name' => 'Uganda', 'name' => 'Tanzania'];
        foreach ($countries as $country) {
            // return $country;
            $tanzania = Shipment::where('country', 'Tanzania')->count();
            $uganda = Shipment::where('country', 'Uganda')->count();
            $kenya = Shipment::where('country', 'Kenya')->count();
        }
        return $country = array(
            'Kenya' => $kenya,
            'Tanzania' => $tanzania,
            'Uganda' => $uganda,
        );
    }


    public function getBranchCount(Request $request)
    {
        if (empty($request->id)) {
            $branches = Branch::setEagerLoads([])->where('country_id', Auth::user()->country_id)->get();
        } else {
            $branches = Branch::setEagerLoads([])->where('country_id', $request->id)->get();
        }        
        $branch_count = [];
        if (empty($request->id)) {
            foreach ($branches as $key => $branch) {
            // return $branch->id;
            // $branch_count[] = Shipment::where('branch_id', $branch->id)->count();
                $branch_count[] = array(
                    'name' => $branch->branch_name,
                    'id' => $key,
                    'count' => Shipment::where('branch_id', $branch->id)->where('country_id', Auth::user()->country_id)->count(),
                );
            }
        } else {
            foreach ($branches as $key => $branch) {
                $branch_count[] = array(
                    'name' => $branch->branch_name,
                    'id' => $key,
                    'count' => Shipment::where('branch_id', $branch->id)->where('country_id', $request->id)->count(),
                );
            }
        }
        return $branch_count;
    }

    public function getChartBranch(Request $request)
    {
		// return Shipment::take(100)->get();
        if (empty($request->id)) {
            $shipments = DB::table('shipments')
                ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
                ->orderBy('id', 'asc')
                ->groupBy('date')
                ->where('country_id', Auth::user()->country_id)
            // ->where('branch_id', Auth::id())
                ->get();
        } else {
            $shipments = DB::table('shipments')
                ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
                ->orderBy('id', 'asc')
                ->groupBy('date')
                ->where('country_id', $request->id)
            // ->where('branch_id', Auth::id())
                ->get();
        }

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

    public function getChartCount(Request $request)
    {
		// return Shipment::take(100)->get();
        if (empty($request->id)) {
            $shipments = DB::table('shipments')
                ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
                ->orderBy('id', 'asc')
                ->groupBy('date')
                ->where('country_id', Auth::user()->country_id)
            // ->where('branch_id', Auth::id())
                ->get();
        } else {
            $shipments = DB::table('shipments')
                ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
                ->orderBy('id', 'asc')
                ->groupBy('date')
                ->where('country_id', $request->id)
            // ->where('branch_id', Auth::id())
                ->get();

        }

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

    public function getBranchShipments(Request $request)
    {
        $branches = Branch::all();
        $A_branch = [];
        foreach ($branches as $branch) {
            if (empty($request->id)) {
                $shipmen_ts = DB::table('shipments')
                    ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date, branch_id'))
                    ->orderBy('id', 'asc')
                    ->groupBy('date')
                    ->where('country_id', Auth::user()->country_id)
            // ->where('branch_id', $branch->id)
                    ->get();
            } else {
                $shipmen_ts = DB::table('shipments')
                    ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date, branch_id'))
                    ->orderBy('id', 'asc')
                    ->groupBy('date')
                    ->where('country_id', $request->id)
        // ->where('branch_id', $branch->id)
                    ->get();
            }
            
            // $shipments = Shipment::where('branch_id', $branch->id)->count();
            // json_decode(json_encode($branch), true);
            $A_branch[] = array_prepend(json_decode(json_encode($shipmen_ts), true), $branch->branch_name, 'name');
        }
        return $shipmen_ts;
    }


    public function getCountryhipments(Request $request)
    {
        $countries = Country::setEagerLoads([])->get();
        $country_count = [];
        foreach ($countries as $key => $country) {
            // return $country->id;
            // $country_count[] = Shipment::where('country_id', $country->id)->count();
            $country_count[] = array(
                'name' => $country->country_name,
                'id' => $key,
                'count' => Shipment::where('country_id', $country->id)->count(),
            );
        }
        return $country_count;
    }

    public function getChartCountry(Request $request)
    {
		// return Shipment::take(100)->get();
        if (empty($request->id)) {
            $shipments = DB::table('shipments')
                ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
                ->orderBy('id', 'asc')
                ->groupBy('date')
                ->where('country_id', Auth::user()->country_id)
            // ->where('country_id', )
                ->get();
        } else {

            $shipments = DB::table('shipments')
                ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
                ->orderBy('id', 'asc')
                ->groupBy('date')
                ->where('country_id', $request->id)
            // ->where('country_id', )
                ->get();
        }

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

    public function countDelivered(Request $request)
    {
        // return $request->all();
        if (empty($request->id)) {
            return Shipment::where('country_id', Auth::user()->country_id)->where('status', 'Delivered')->count();
        } else {
            return Shipment::where('country_id', $request->id)->where('status', 'Delivered')->count();
        }
    }

    public function countPending(Request $request)
    {
        // return Shipment::count();
        if (empty($request->id)) {
            return Shipment::where('country_id', Auth::user()->country_id)->where('status', '!=', 'Delivered')->where('status', '!=', 'Cancelled')->count();
        } else {
            return Shipment::where('country_id', $request->id)->where('status', '!=', 'Delivered')->where('status', '!=', 'Cancelled')->count();
        }
    }



    public function countCountShipments(Request $request)
    {
        // return Shipment::count();
        if (empty($request->id)) {
            return Shipment::where('country_id', Auth::user()->country_id)->count();
        } else {
            return Shipment::where('country_id', $request->id)->count();
        }
    }

    public function countOrders(Request $request)
    {
        return Shipment::count();
    }
}
