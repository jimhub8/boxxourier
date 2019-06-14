<?php

namespace App\Http\Controllers;

use App\Rows;
use App\Shipment;
use Illuminate\Http\Request;
use Auth;

class RowsController extends Controller
{
	public function getRows()
	{
		$A_row = Auth::user()->rows;
		$rows_select = [];
		foreach ($A_row as $row) {
			$rows_select[] = $row->text;
		}
		$arr_row = Rows::select('text')->get();
		$to_arr = $arr_row->toArray();
		$arr_flat = array_flatten($to_arr);
		$row_res = [];
		foreach ($arr_row as $row) {
			if (in_array($row->text, $rows_select)) {
				$row_res[] = [$row->text => true];
			} else {
				$row_res[] = [$row->text => false];
			}
		}
		return array_collapse($row_res);
	}
	public function getAllRows()
	{
		return $arr_row = Auth::user()->rows;
	}
	public function rows(Request $request)
	{
		// return $request->all();
		$all_rows = serialize($request->all());
		return $rows = Rows::updateOrCreate(
			['user_id' => Auth::id()],
			['rows' => $all_rows]
		);
	}


	public function importBranch(Request $request)
	{
		// var_dump($request->all());die;
		$user = User::find($request->client);
		if ($request->file('shipment')) {
			$path = $request->file('shipment')->getRealPath();
			$data = Excel::load($path, function ($reader) {

			})->get();

			if (!empty($data) && $data->count()) {
				foreach ($data->toArray() as $row) {
					if (!empty($row)) {
						$dataArray[] =
							[
							'branch_name' => $row['branch_name'],
							'branch_id' => Auth::user()->branch_id,
							'address' => $row['address'],
							'phone' => $row['phone'],
							'email' => $row['email'],
							'user_id' => Auth::id(),
						];
					}
				}
				if (!empty($dataArray)) {
					Shipment::insert($dataArray);
				}
				return redirect('courier#/branches');

			}
		} else {
			var_dump('something went wrong');
		}
	}

	public function notprinted(Request $request, $id)
	{
		$shipment = Shipment::find($id);
		$shipment->printReceipt = 0;
		$shipment->printed = 0;
		$shipment->save();
		return $shipment;
	}
	public function printed(Request $request, $id)
	{
		// return redirect('/test');
		$shipment = Shipment::find($id);
		$shipment->printReceipt = 1;
		$shipment->printed = 1;
		$shipment->save();
		return $shipment;
	}
}
