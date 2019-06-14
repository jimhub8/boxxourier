<?php

namespace App\Http\Controllers;

use App\Container;
use App\Http\Requests\ContainerRequest;
use App\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContainerController extends Controller {
	public function conupdateStatus(Request $request, $id) {
		// return $request->all();
		$container = Container::find($id);
		$container->remark = $request->remark;
		$container->status = $request->status;
		$container->city = $request->city;
		$container->save();
	}
	public function getShipmentArray($id) {
		$record = Container::find($id);
		$shipments = unserialize($record->shipments);
		// var_dump($record); die;
		return Shipment::whereIn('id', $shipments)->get();
		// array_has($shipments_id)
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getContainers() {
		return json_decode(json_encode(Container::where('branch_id', Auth::user()->branch_id)->get()));
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(ContainerRequest $request) {
		// return $request->all();
		$container = new Container;
		$container->bar_code = $request->bar_code;
		$container->address = $request->address;
		$container->city = $request->city;
		$container->assign_staff = $request->assign_staff;
		$container->derivery_date = $request->derivery_date;
		$container->derivery_time = $request->derivery_time;
		$container->branch_id = Auth::user()->branch_id;
		$container->save();
		return $container;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Container  $container
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Container $container) {
		$container = Container::find($request->id);
		$container->bar_code = $request->bar_code;
		$container->address = $request->address;
		$container->city = $request->city;
		$container->assign_staff = $request->assign_staff;
		$container->derivery_date = $request->derivery_date;
		$container->derivery_time = $request->derivery_time;
		$container->save();
		return $container;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Container  $container
	 * @return \Illuminate\Http\Response
	 */
	public function AddShipments(Request $request, Container $container, $id) {
		// return $request->all();
		$container = Container::find($id);
		$serializedArr = serialize($request->selected);
		$container->shipments = $serializedArr;
		$container->save();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Container  $container
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Container $container) {
		//
	}

	public function assigndialog(Request $request, Container $container, $id)
	{
		// return $request->all();
		$container = Container::find($id);
		// return $request->driver;
		$container->driver = $request->driver['id'];
		$container->save();
	}
}
