<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
	public function displayReport(Request $request)
	{
		// dd($request->all());
		$date_array = array(
			'start_date' => $request->start_date,
			'end_date' => $request->end_date,
		);
		$status = $request->status;
		if (Auth::user()->hasRole('Client')) {
			return Shipment::where('client_id', Auth::id())->latest()->setEagerLoads([])->whereBetween('created_at', [$date_array])->take(15000)->where('status', $status)->get();
		} else {
			return Shipment::where('country_id', Auth::user()->country_id)->latest()->setEagerLoads([])->whereBetween('created_at', [$date_array])->take(15000)->where('status', $status)->get();
		}
	}
}
