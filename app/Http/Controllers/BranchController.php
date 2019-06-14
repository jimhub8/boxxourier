<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Shipment;
use App\Http\Requests\BranchesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();
        return Branch::where('country_id', $user->country_id)->orderBy('branch_name', 'asc')->get();
    }
    
    public function show($id)
    {
        return Branch::find($id);
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
		$this->Validate($request, [
			// 'email' => 'required|email',
			// 'phone' => 'required|numeric',
			// 'address' => 'required',
			'country_id' => 'required',
			'branch_name' => 'required',
		]);
		// $branch = new Branch;
		$branch_name = $request->branch_name;
		$phone = $request->phone;
		$country_id = $request->country_id;
		$address = $request->address;
		$email = $request->email;
		$user_id = Auth::id();
		$branch_id = Auth::user()->branch_id;

		$branch = Branch::updateOrCreate(
			['branch_name' => $branch_name, 'country_id' => $country_id],
			['phone' => $phone, 'address' => $address, 'user_id' => $user_id, 'email' => $email, 'branch_id' => $branch_id]
		);
		// $branch->save();
		return $branch;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Branch  $branch
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Branch $branch)
	{
		// return $request->all();
		$branch = Branch::find($request->id);
		$branch->branch_name = $request->branch_name;
		$branch->phone = $request->phone;
		$branch->address = $request->address;
		$branch->country_id = $request->country_id;
		$branch->email = $request->email;
		$branch->save();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Branch  $branch
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Branch $branch)
	{
		Branch::find($branch->id)->delete();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getBranch()
	{
		return Branch::where('country_id', Auth::id())->get();
	}

	public function getBranchShip(Request $request, $id)
	{
		return Branch::where('id', $id)->get();
	}

	public function getBranchEger()
	{
		return Branch::setEagerLoads([])->orderBy('branch_name')->where('country_id', Auth::user()->country_id)->get();
	}

	public function getBranchC()
	{
		return Branch::count();
	}

	public function getShipBranch(Request $request)
	{
		// return $request->all();
		if ($request->selectCl) {
			// return 'test';
			return Shipment::where('client_id', $request->selectCl['id'])->take(500)->latest()->get();
		}
		if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
			if ($request->select['id'] == 'all') {
				if ($request->selectStatus['name'] == 'All') {
					if ($request->selectAss['Assigned'] == 'All') {
						return Shipment::whereNotNull('branch_id')->take(500)->latest()->get();
					} else {
						if ($request->selectAss['Assigned'] == 'Assigned') {
							return Shipment::whereNotNull('branch_id')->take(500)->latest()->get();
						} else {
							return Shipment::whereNull('branch_id')->take(500)->latest()->get();
						}
					}
				} else {
					if ($request->selectAss['Assigned'] == 'All') {
						return Shipment::whereNotNull('branch_id')->where('status', $request->selectStatus['name'])->take(500)->latest()->get();
					} else {
						if ($request->selectAss['Assigned'] == 'Assigned') {
							return Shipment::whereNotNull('branch_id')->where('status', $request->selectStatus['name'])->take(500)->latest()->get();
						} else {
							return Shipment::whereNull('branch_id')->where('status', $request->selectStatus['name'])->take(500)->latest()->get();
						}
					}
				}

			} else {
				if ($request->selectStatus['name'] == 'All') {
					if ($request->selectAss['Assigned'] == 'All') {
						return Shipment::whereNotNull('branch_id')->where('branch_id', $request->select['id'])->take(500)->latest()->get();
					} else {
						if ($request->selectAss['Assigned'] == 'Assigned') {
							return Shipment::whereNotNull('branch_id')->where('branch_id', $request->select['id'])->take(500)->latest()->get();
						} else {
							return Shipment::whereNull('branch_id')->where('branch_id', $request->select['id'])->take(500)->latest()->get();
						}
					}
				} else {
					if ($request->selectAss['Assigned'] == 'All') {
						return Shipment::whereNotNull('branch_id')->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->take(500)->latest()->get();
					} else {
						if ($request->selectAss['Assigned'] == 'Assigned') {
							return Shipment::whereNotNull('branch_id')->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->take(500)->latest()->get();
						} else {
							return Shipment::whereNull('branch_id')->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->take(500)->latest()->get();
						}
					}
				}
			}
		} else {
			if ($request->select['id'] == 'all') {
				if ($request->selectStatus['name'] == 'All') {
					if ($request->selectAss['Assigned'] == 'All') {
						return Shipment::whereNotNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->latest()->get();
					} else {
						if ($request->selectAss['Assigned'] == 'Assigned') {
							return Shipment::whereNotNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->latest()->get();
						} else {
							return Shipment::whereNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->latest()->get();
						}
					}
				} else {
					if ($request->selectAss['Assigned'] == 'All') {
						return Shipment::whereNotNull('branch_id')->take(500)->where('status', $request->selectStatus['name'])->latest()->get();
					} else {
						if ($request->selectAss['Assigned'] == 'Assigned') {
							return Shipment::whereNotNull('branch_id')->where('status', $request->selectStatus['name'])->take(500)->latest()->get();
						} else {
							return Shipment::whereNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->where('status', $request->selectStatus['name'])->latest()->get();
						}
					}
				}
			} else {

				if ($request->selectStatus['name'] == 'All') {
					if ($request->selectAss['Assigned'] == 'All') {
						return Shipment::whereNotNull('branch_id')->where('branch_id', $request->select['id'])->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->latest()->get();
					// return Shipment::whereNotNull('branch_id')->latest()->take(500)->get();	
					} else {
						if ($request->selectAss['Assigned'] == 'Assigned') {
							return Shipment::whereNotNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->take(500)->latest()->get();
						} else {
							return Shipment::whereNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->take(500)->latest()->get();
						}
					}
				} else {
					if ($request->selectAss['Assigned'] == 'All') {
						return Shipment::whereNotNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->where('status', $request->selectStatus['name'])->where('branch_id', $request->select['id'])->latest()->get();
					} else {
						if ($request->selectAss['Assigned'] == 'Assigned') {
							return Shipment::whereNotNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('status', $request->selectStatus['name'])->take(500)->where('branch_id', $request->select['id'])->latest()->get();
						} else {
							return Shipment::whereNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->where('status', $request->selectStatus['name'])->where('branch_id', $request->select['id'])->latest()->get();
						}
					}
				}
			}
		}
	}

}
