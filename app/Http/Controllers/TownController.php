<?php

namespace App\Http\Controllers;

use App\Town;
use Illuminate\Http\Request;
use Auth;

class TownController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$this->Validate($request, [
			'town_name' => 'required',
		]);
        // return $request->all();
        $town = new Town;
        $town->town_name = $request->town_name;
        $town->user_id = Auth::id();
        $town->save();
        return $town;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Town  $town
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Town $town)
    {
        // return $request->all();
        $town = Town::find($request->id);
        $town->town_name = $request->town_name;
        $town->save();
        return $town;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Town  $town
     * @return \Illuminate\Http\Response
     */
    public function destroy(Town $town)
    {
        //
    }

    public function getTowns()
    {
        return Town::all();
    }
	public function getTownCharge() {
		return Town::with('charges')->get();
	}
}
