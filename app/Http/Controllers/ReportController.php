<?php

namespace App\Http\Controllers;

use App\Shipment;
// use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Maatwebsite\Excel\Facades\Excel;
// use PdfReport;
// use ExcelReport;
// use Illuminate\Support\Carbon;
// use App\Exports\Reports;
// use Maatwebsite\Excel\Exporter;
// use Maatwebsite\Excel\Facades\Excel;
use PDF;
use App\Mail\ReportMail;

class ReportController extends Controller
{
	public function branchesExpo(Request $request)
	{
		// return $request->all();
		$date_array = array(
			'start_date' => $request->branch['start_date'],
			'end_date' => $request->branch['end_date'],
		);
		if (Auth::user()->hasRole('Client')) {
			if ($request->branch_status != [] && $request->branch == []) {
				$status = $request->branch_status['status'];
				return Shipment::where('client_id', Auth::id())->setEagerLoads([])->whereBetween('dispatch_date', [$date_array])->where('status', $status)->take(15000)->latest()->get();
			} elseif ($request->branch_status == [] && $request->branch != []) {
				$branch_id = $request->branch['branch_id'];
				$branch_id = $request->branch_status['branch_id'];
				return Shipment::where('client_id', Auth::id())->setEagerLoads([])->whereBetween('dispatch_date', [$date_array])->where('branch_id', $branch_id)->take(15000)->latest()->get();
			} elseif ($request->branch_status != [] && $request->branch != []) {
				$branch_id = $request->branch['branch_id'];
				$status = $request->branch_status['status'];
				return Shipment::where('client_id', Auth::id())->setEagerLoads([])->whereBetween('dispatch_date', [$date_array])->where('status', $status)->where('branch_id', $branch_id)->take(15000)->latest()->get();
			} else {
				return Shipment::where('client_id', Auth::id())->latest()->setEagerLoads([])->whereBetween('created_at', [$date_array])->take(15000)->where('branch_id', $request->branch['branch_id'])->where('status', $status)->get();
			}
		} else {
			if ($request->branch_status != [] && $request->branch == []) {
				$status = $request->branch_status['status'];
				return Shipment::where('country_id', Auth::user()->country_id)->setEagerLoads([])->whereBetween('dispatch_date', [$date_array])->where('status', $status)->take(15000)->latest()->get();
			} elseif ($request->branch_status == [] && $request->branch != []) {
				$branch_id = $request->branch['branch_id'];
				$branch_id = $request->branch_status['branch_id'];
				return Shipment::where('country_id', Auth::user()->country_id)->setEagerLoads([])->whereBetween('dispatch_date', [$date_array])->where('branch_id', $branch_id)->take(15000)->latest()->get();
			} elseif ($request->branch_status != [] && $request->branch != []) {
				$branch_id = $request->branch['branch_id'];
				$status = $request->branch_status['status'];
				return Shipment::where('country_id', Auth::user()->country_id)->setEagerLoads([])->whereBetween('dispatch_date', [$date_array])->where('status', $status)->where('branch_id', $branch_id)->take(15000)->latest()->get();
			} else {
				return Shipment::where('country_id', Auth::user()->country_id)->latest()->setEagerLoads([])->whereBetween('created_at', [$date_array])->take(15000)->where('branch_id', $request->branch['branch_id'])->where('status', $status)->get();
			}
		}

		// return Shipment::where('country_id', Auth::user()->country_id)->latest()->setEagerLoads([])->whereBetween('created_at', [$date_array])->take(15000)->where('branch_id', $request->branch['branch_id'])->where('status', $status)->get();
	}


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
	public function DriverReport(Request $request)
	{
		// dd($request->all());
		$date_array = array(
			'start_date' => $request->start_date,
			'end_date' => $request->end_date,
		);
		return Shipment::where('country_id', Auth::user()->country_id)->latest()->setEagerLoads([])->whereBetween('created_at', [$date_array])->take(15000)->where('driver', $request->rinder_id)->get();
	}

	public function userDateExpo(Request $request)
	{
		$date_array = array(
			'start_date' => $request->start_date,
			'end_date' => $request->end_date,
		);
		return Shipment::where('country_id', Auth::user()->country_id)->latest()->where('client_id', $request->client_id)->setEagerLoads([])->whereBetween('created_at', [$date_array])->take(15000)->get();
	}

	public function DelivReport(Request $request)
	{
		// return $request->all();
		$date_array = array(
			'start_date' => $request->start_date,
			'end_date' => $request->end_date,
		);
		$Update_array = array(
			'Upstart_date' => $request->Upstart_date,
			'Upend_date' => $request->Upend_date,
		);
		$status = $request->status;
		$branch_id = $request->branch_id;
		if (Auth::user()->hasRole('Client')) {
			if ($status == 'Dispatched') {
				return Shipment::where('country_id', Auth::user()->country_id)->latest()->setEagerLoads([])->whereBetween('dispatch_date', [$date_array])->whereBetween('created_at', [$Update_array])->take(15000)->get();
			} else {
				return Shipment::where('country_id', Auth::user()->country_id)->latest()->setEagerLoads([])->whereBetween('derivery_date', [$date_array])->whereBetween('created_at', [$Update_array])->where('status', $status)->take(15000)->get();
			}
		} else {
			if (empty($branch_id)) {
				if ($status == 'Dispatched') {
					return Shipment::where('country_id', Auth::user()->country_id)->latest()->setEagerLoads([])->whereBetween('dispatch_date', [$date_array])->whereBetween('created_at', [$Update_array])->take(15000)->get();
				} else {
					return Shipment::where('country_id', Auth::user()->country_id)->latest()->setEagerLoads([])->whereBetween('derivery_date', [$date_array])->whereBetween('created_at', [$Update_array])->where('status', $status)->take(15000)->get();
				}
			} else {
				if ($status == 'Dispatched') {
					return Shipment::where('country_id', Auth::user()->country_id)->where('branch_id', $branch_id)->latest()->setEagerLoads([])->whereBetween('dispatch_date', [$date_array])->whereBetween('created_at', [$Update_array])->take(15000)->get();
				} else {
					return Shipment::where('country_id', Auth::user()->country_id)->where('branch_id', $branch_id)->latest()->setEagerLoads([])->whereBetween('derivery_date', [$date_array])->whereBetween('created_at', [$Update_array])->where('status', $status)->take(15000)->get();
				}
			}
		}
	}

	public function ProdReport(Request $request)
	{
		// return $request->all();
		$start_date = $request->start_date;
		$end_date = $request->end_date;
		$email = $request->email;
		$status = $request->status;

		$date_array = array(
			'start_date' => $start_date,
			'end_date' => $end_date,
		);

		if ($status == 'Dispatched') {
			return Shipment::where('country_id', Auth::user()->country_id)->latest()->setEagerLoads([])->whereBetween('dispatch_date', [$date_array])->where('client_email', 'LIKE', "%{$email}%")->take(15000)->get();
		} else {
			return Shipment::where('country_id', Auth::user()->country_id)->latest()->setEagerLoads([])->whereBetween('derivery_date', [$date_array])->where('client_email', 'LIKE', "%{$email}%")->where('status', $status)->take(15000)->get();
		}
	}

	public function paymentReport(Request $request)
	{
		// return $request->all();
		$start_date = $request->start_date;
		$end_date = $request->end_date;

		$date_array = array(
			'start_date' => $start_date,
			'end_date' => $end_date,
		);

		if ($request->paid == 1) {
			return Shipment::where('country_id', Auth::user()->country_id)->latest()->setEagerLoads([])->whereBetween('created_at', [$date_array])->where('paid', true)->take(15000)->get();
		} else {
			return Shipment::where('country_id', Auth::user()->country_id)->latest()->setEagerLoads([])->whereBetween('created_at', [$date_array])->where('paid', false)->take(15000)->get();
		}
	}

	public function logsReport(Request $request)
	{
		return $request->all();
		$start_date = $request->start_date;
		$end_date = $request->end_date;

		$date_array = array(
			'start_date' => $start_date,
			'end_date' => $end_date,
		);

		if ($request->paid == 1) {
			return Logs::where('country_id', Auth::user()->country_id)->latest()->setEagerLoads([])->whereBetween('created_at', [$date_array])->where('paid', true)->take(15000)->get();
		} else {
			return Shipment::where('country_id', Auth::user()->country_id)->latest()->setEagerLoads([])->whereBetween('created_at', [$date_array])->where('paid', false)->take(15000)->get();
		}
	}
	public function countryReport(Request $request)
	{
		// return $request->all();
		$start_date = $request->start_date;
		$end_date = $request->end_date;

		$date_array = array(
			'start_date' => $start_date,
			'end_date' => $end_date,
		);
		return Shipment::where('country_id', $request->country_id)->latest()->setEagerLoads([])->whereBetween('created_at', [$date_array])->get();
	}

	public function status_reports(Request $request)
	{
		// return $request->all();
		$status = $request->data['status']; 
		$date_array = array(
			'start_date' => $request->data['start_date'],
			'end_date' => $request->data['end_date'],
		);
		if (Auth::user()->hasRole('Client')) {
			// return $date_array;
			return Shipment::where('client_id', Auth::id())->latest()->setEagerLoads([])->whereBetween('created_at', [$date_array])->where('status', $status)->get();
		} else {
			return Shipment::where('country_id', Auth::user()->country_id)->latest()->setEagerLoads([])->whereBetween('created_at', [$date_array])->where('status', $status)->get();
		}
	}
}
