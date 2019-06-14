<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipment;
use App\ShipmentStatus;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
	public function UpdateFollowUp(Request $request, Shipment $shipment)
	{
		// return $request->all();
		$id = [];
		foreach ($request->selected as $selectedItems) {
			$id[] = $selectedItems['id'];
		}
		$status = $request->form['status'];
		$derivery_time = $request->form['derivery_time'];
		$remark = $request->form['remark'];
		// $location = $request->form['location'];
		$derivery_date = $request->form['delivery_date'];
		if ($status == 'Returned') {
			if (empty($remark)) {
				// $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['status' => $status, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'driver' => null, 'derivery_status' => $status]);
				foreach ($id as $value) {
                    $shipment = Shipment::find($value);
                    $shipment->derivery_date = $derivery_date;
                    $shipment->status = $status;
                    // $shipment->speciial_instruction = $remark;
                    // $shipment->remark = $remark;
                    $shipment->derivery_status = $status;
                    $shipment->save();
                }
			} else {
				// $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['status' => $status, 'remark' => $remark, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'speciial_instruction' => $remark, 'driver' => null, 'derivery_status' => $status]);
				foreach ($id as $value) {
                    $shipment = Shipment::find($value);
                    $shipment->derivery_date = $derivery_date;
                    $shipment->status = $status;
                    $shipment->speciial_instruction = $remark;
                    $shipment->remark = $remark;
                    $shipment->derivery_status = $status;
                    $shipment->save();
                }
			}
		} elseif ($status == 'Delivered' || $status == 'Cancelled') {
			if (empty($remark)) {
				// $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['status' => $status, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'derivery_status' => $status]);
				
				foreach ($id as $value) {
                    $shipment = Shipment::find($value);
                    $shipment->derivery_date = $derivery_date;
                    $shipment->status = $status;
                    // $shipment->speciial_instruction = $remark;
                    // $shipment->remark = $remark;
                    $shipment->derivery_status = $status;
                    $shipment->save();
                }
			} else {
				// $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['status' => $status, 'remark' => $remark, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'speciial_instruction' => $remark, 'derivery_status' => $status]);
				
				foreach ($id as $value) {
                    $shipment = Shipment::find($value);
                    $shipment->derivery_date = $derivery_date;
                    $shipment->status = $status;
                    $shipment->speciial_instruction = $remark;
                    $shipment->remark = $remark;
                    $shipment->derivery_status = $status;
                    $shipment->save();
                }
			}
		} elseif ($status == 'Dispatched') {
			if (empty($remark)) {
				// $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['status' => $status, 'dispatch_date' => now(), 'derivery_status' => $status]);
				
				foreach ($id as $value) {
                    $shipment = Shipment::find($value);
                    $shipment->dispatch_date = $now();
                    $shipment->status = $status;
                    // $shipment->speciial_instruction = $remark;
                    // $shipment->remark = $remark;
                    $shipment->derivery_status = $status;
                    $shipment->save();
                }
			} else {
				// $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['status' => $status, 'remark' => $remark, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'speciial_instruction' => $remark, 'derivery_status' => $status]);
				
				foreach ($id as $value) {
                    $shipment = Shipment::find($value);
                    $shipment->derivery_date = $derivery_date;
                    $shipment->status = $status;
                    $shipment->speciial_instruction = $remark;
                    $shipment->remark = $remark;
                    $shipment->derivery_status = $status;
                    $shipment->save();
                }
			}
		} else {
			if (empty($remark)) {
				// $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['derivery_status' => $status]);
				
				foreach ($id as $value) {
                    $shipment = Shipment::find($value);
                    // $shipment->derivery_date = $derivery_date;
                    // $shipment->status = $status;
                    // $shipment->speciial_instruction = $remark;
                    // $shipment->remark = $remark;
                    $shipment->derivery_status = $status;
                    $shipment->save();
                }
			} else {
				// $shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['remark' => $remark, 'derivery_status' => $status]);
				
				foreach ($id as $value) {
                    $shipment = Shipment::find($value);
                    // $shipment->derivery_date = $derivery_date;
                    // $shipment->status = $status;
                    $shipment->speciial_instruction = $remark;
                    $shipment->remark = $remark;
                    $shipment->derivery_status = $status;
                    $shipment->save();
                }
			}
		}
		$phones = Shipment::setEagerLoads([])->select('id', 'client_phone', 'client_name', 'bar_code')->whereIn('id', $id)->get();
		$shipStatus = Shipment::setEagerLoads([])->whereIn('id', $id)->get();
		foreach ($phones as $statuses) {
			$statusUpdate = new ShipmentStatus();
			$statusUpdate->remark = $request->form['remark'];
			if (empty($remark)) {
				$statusUpdate->status = $request->form['status'];
			}
			$statusUpdate->location = $request->form['location'];
			// $statusUpdate->derivery_time = $request->form['derivery_time'];
			$statusUpdate->user_id = Auth::id();
			$statusUpdate->branch_id = Auth::user()->branch_id;
			$statusUpdate->shipment_id = $statuses->id;
			$statusUpdate->save();
		}
		// if ($status == 'Scheduled') {
		// 	foreach ($phones as $phone) {
		// 		$this->send_sms($phone->client_phone, 'Dear ' . $phone->client_name . ', Your shipment (waybill number: ' . $phone->bar_code . ')  has been scheduled to be delivered on ' . $derivery_date);
		// 	}
		// } elseif ($status == 'Delivered') {
		// 	foreach ($phones as $phone) {
		// 		$this->send_sms($phone->client_phone, 'Dear ' . $phone->client_name . ', Your shipment (waybill number: ' . $phone->bar_code . ') has been delivered');
		// 	}
		// } 
		// $shipStatus->statuses()->saveMany($shipStatus);
		return $shipment;
	}


	public function UpdateFollowSUp(Request $request, Shipment $shipment, $id)
	{
		// return $request->all();
		$shipment = Shipment::find($request->id);
		// $shipment->status = $request->formobg['status'];
		$status = $request->formobg['status'];
		// var_dump($request->formobg['status']); die;
		// $shipment->remark = $request->formobg['remark'];
		$shipment->speciial_instruction = $request->formobg['speciial_instruction'];
		if ($status == 'Scheduled') {
			$shipment->derivery_date = $request->formobg['derivery_date'];
			$shipment->derivery_time = $request->formobg['derivery_time'];
			$shipment->derivery_status = $status;
			$shipment->status = $status;
		} elseif ($status == 'Delivered') {
			$shipment->derivery_date = $request->formobg['derivery_date'];
			$shipment->derivery_status = $status;
			$shipment->derivery_time = $request->formobg['derivery_time'];
			$shipment->receiver_id = $request->formobg['receiver_id'];
			$shipment->receiver_name = $request->formobg['receiver_name'];
			$shipment->status = $status;
		} elseif ($status == 'Returned' || $status == 'Cancelled') {
			$shipment->derivery_status = $status;
			$shipment->status = $status;
			$shipment->driver = null;
		} elseif ($status == 'Dispatched') {
			$shipment->derivery_status = $status;
			$shipment->dispatch_date = now();
		} else {
			$shipment->derivery_status = $status;
		}
		if ($shipment->save()) {
			$shipStatus = Shipment::find($id);
			$statusUpdate = new ShipmentStatus;
			$statusUpdate->remark = $request->formobg['speciial_instruction'];
			$statusUpdate->status = $status;
			$statusUpdate->location = $request->formobg['location'];
			// $statusUpdate->derivery_time = $request->formobg['derivery_time'];
			$statusUpdate->user_id = Auth::id();
			$statusUpdate->branch_id = Auth::user()->branch_id;
			$statusUpdate->shipment_id = $id;
			// return $statusUpdate;
			$statusUpdate->save();
		}

		// if ($request->formobg['status'] == 'Scheduled') {
		// 	$this->send_sms($request->formobg['client_phone'], 'Dear ' . $request->formobg['client_name'] . ', Your shipment (waybill number: ' . $request->formobg['bar_code'] . ')  has been scheduled to be delivered on ' . $request->formobg['derivery_date']);
		// } elseif ($request->formobg['status'] == 'Delivered') {
		// 	$this->send_sms($request->formobg['client_phone'], 'Dear ' . $request->formobg['client_name'] . ', Your shipment (waybill number: ' . $request->formobg['bar_code'] . ') has been delivered');
		// }
		return $shipment;
	}
}
