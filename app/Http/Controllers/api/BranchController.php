<?php

namespace App\Http\Controllers\api;

use App\Branch;
use App\Http\Controllers\Controller;
use App\Http\Resources\BranchResource;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();
        $branch = Branch::where('country_id', $user->country_id)->orderBy('branch_name', 'asc')->get();
        return BranchResource::collection($branch);
    }

    public function show($id)
    {
        $branch = Branch::where('id',$id)->get();
        return BranchResource::collection($branch);
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
            // 'country_id' => 'required',
            // 'branch_name' => 'required',
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
        return BranchResource::collection($branch);
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
        $branch = Branch::where('id',$id)->get();
        $branch->branch_name = $request->branch_name;
        $branch->phone = $request->phone;
        $branch->address = $request->address;
        $branch->country_id = $request->country_id;
        $branch->email = $request->email;
        $branch->save();
        return BranchResource::collection($branch);
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
        $branch = Branch::where('country_id', Auth::id())->get();
        return BranchResource::collection($branch);
    }

    public function getBranchShip(Request $request, $id)
    {
        $branch = Branch::where('id', $id)->get();
        return BranchResource::collection($branch);
    }

    public function getBranchEger()
    {
        $branch = Branch::setEagerLoads([])->orderBy('branch_name')->where('country_id', Auth::user()->country_id)->get();
        return BranchResource::collection($branch);
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
            $shipments = Shipment::where('client_id', $request->selectCl['id'])->take(500)->latest()->get();
        }
        if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
            if ($request->select['id'] == 'all') {
                if ($request->selectStatus['name'] == 'All') {
                    if ($request->selectAss['Assigned'] == 'All') {
                        $shipments = Shipment::whereNotNull('branch_id')->take(500)->latest()->get();
                    } else {
                        if ($request->selectAss['Assigned'] == 'Assigned') {
                            $shipments = Shipment::whereNotNull('branch_id')->take(500)->latest()->get();
                        } else {
                            $shipments = Shipment::whereNull('branch_id')->take(500)->latest()->get();
                        }
                    }
                } else {
                    if ($request->selectAss['Assigned'] == 'All') {
                        $shipments = Shipment::whereNotNull('branch_id')->where('status', $request->selectStatus['name'])->take(500)->latest()->get();
                    } else {
                        if ($request->selectAss['Assigned'] == 'Assigned') {
                            $shipments = Shipment::whereNotNull('branch_id')->where('status', $request->selectStatus['name'])->take(500)->latest()->get();
                        } else {
                            $shipments = Shipment::whereNull('branch_id')->where('status', $request->selectStatus['name'])->take(500)->latest()->get();
                        }
                    }
                }

            } else {
                if ($request->selectStatus['name'] == 'All') {
                    if ($request->selectAss['Assigned'] == 'All') {
                        $shipments = Shipment::whereNotNull('branch_id')->where('branch_id', $request->select['id'])->take(500)->latest()->get();
                    } else {
                        if ($request->selectAss['Assigned'] == 'Assigned') {
                            $shipments = Shipment::whereNotNull('branch_id')->where('branch_id', $request->select['id'])->take(500)->latest()->get();
                        } else {
                            $shipments = Shipment::whereNull('branch_id')->where('branch_id', $request->select['id'])->take(500)->latest()->get();
                        }
                    }
                } else {
                    if ($request->selectAss['Assigned'] == 'All') {
                        $shipments = Shipment::whereNotNull('branch_id')->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->take(500)->latest()->get();
                    } else {
                        if ($request->selectAss['Assigned'] == 'Assigned') {
                            $shipments = Shipment::whereNotNull('branch_id')->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->take(500)->latest()->get();
                        } else {
                            $shipments = Shipment::whereNull('branch_id')->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->take(500)->latest()->get();
                        }
                    }
                }
            }
        } else {
            if ($request->select['id'] == 'all') {
                if ($request->selectStatus['name'] == 'All') {
                    if ($request->selectAss['Assigned'] == 'All') {
                        $shipments = Shipment::whereNotNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->latest()->get();
                    } else {
                        if ($request->selectAss['Assigned'] == 'Assigned') {
                            $shipments = Shipment::whereNotNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->latest()->get();
                        } else {
                            $shipments = Shipment::whereNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->latest()->get();
                        }
                    }
                } else {
                    if ($request->selectAss['Assigned'] == 'All') {
                        $shipments = Shipment::whereNotNull('branch_id')->take(500)->where('status', $request->selectStatus['name'])->latest()->get();
                    } else {
                        if ($request->selectAss['Assigned'] == 'Assigned') {
                            $shipments = Shipment::whereNotNull('branch_id')->where('status', $request->selectStatus['name'])->take(500)->latest()->get();
                        } else {
                            $shipments = Shipment::whereNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->where('status', $request->selectStatus['name'])->latest()->get();
                        }
                    }
                }
            } else {

                if ($request->selectStatus['name'] == 'All') {
                    if ($request->selectAss['Assigned'] == 'All') {
                        $shipments = Shipment::whereNotNull('branch_id')->where('branch_id', $request->select['id'])->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->latest()->get();
                        // $shipments = Shipment::whereNotNull('branch_id')->latest()->take(500)->get();
                    } else {
                        if ($request->selectAss['Assigned'] == 'Assigned') {
                            $shipments = Shipment::whereNotNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->take(500)->latest()->get();
                        } else {
                            $shipments = Shipment::whereNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->take(500)->latest()->get();
                        }
                    }
                } else {
                    if ($request->selectAss['Assigned'] == 'All') {
                        $shipments = Shipment::whereNotNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->where('status', $request->selectStatus['name'])->where('branch_id', $request->select['id'])->latest()->get();
                    } else {
                        if ($request->selectAss['Assigned'] == 'Assigned') {
                            $shipments = Shipment::whereNotNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('status', $request->selectStatus['name'])->take(500)->where('branch_id', $request->select['id'])->latest()->get();
                        } else {
                            $shipments = Shipment::whereNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->where('status', $request->selectStatus['name'])->where('branch_id', $request->select['id'])->latest()->get();
                        }
                    }
                }
            }
        }
        return ShipmentResource::collection($shipment);
    }

}
