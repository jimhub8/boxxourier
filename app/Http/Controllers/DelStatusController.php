<?php

namespace App\Http\Controllers;

use App\DelStatus;
use Illuminate\Http\Request;

class DelStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DelStatus::select('name')->orderBy('name', 'ASC')->get();
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
        $status = new DelStatus;
        $status->name = $request->name;
        $status->save();
        return $status;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DelStatus  $delStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DelStatus $delStatus)
    {
        // return $request->all();
        $status = DelStatus::find($request->id);
        $status->name = $request->name;
        $status->save();
        return $status;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DelStatus  $delStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(DelStatus $delStatus)
    {
        //
    }
}
