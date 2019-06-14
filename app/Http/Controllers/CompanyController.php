<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\CompanyRequest;
use App\User;
use Auth;
use Illuminate\Http\Request;

class CompanyController extends Controller {

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CompanyRequest $request) {
		// return $request->all();
		$company = new Company;
		if ($request->location) {
			$location = serialize($request->location);
			// Location
			$loc = $request->location;
			$longitude = $loc['longitude'];
			$latitude = $loc['latitude'];
			$country = $loc['country'];

			if (in_array('administrative_area_level_1', $loc)) {
				$locality = $loc['administrative_area_level_1'];
			} elseif (in_array('locality', $loc)) {
				$locality = $loc['locality'];
			} else {
				$locality = '';
			}

			$company->longitude = $longitude;
			$company->latitude = $latitude;
			$company->country = $country;
			$company->locality = $locality;
			$company->location = $location;
		}
		// Location
		$company->company_name = $request->data['company_name'];
		$company->email = $request->data['email'];
		$company->phone = $request->data['phone'];
		$company->address = $request->data['address'];
		$company->admin = $request->data['admin']['id'];
		$company->user_id = Auth::id();
		$company->save();
		return $company;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Company  $company
	 * @return \Illuminate\Http\Response
	 */
	public function companupdate(Request $request, Company $company) {
		// return $request->all();
		$company = Company::find($request->id);
		// Location
		if ($request->location) {
			$location = serialize($request->location);
			$loc = $request->location;
			$longitude = $loc['longitude'];
			$latitude = $loc['latitude'];
			$country = $loc['country'];

			if (in_array('administrative_area_level_1', $loc)) {
				$locality = $loc['administrative_area_level_1'];
			} elseif (in_array('locality', $loc)) {
				$locality = $loc['locality'];
			} else {
				$locality = '';
			}
			$company->longitude = $longitude;
			$company->latitude = $latitude;
			$company->country = $country;
			$company->locality = $locality;
			$company->location = $location;
		}
		// Location
		if ($request->data['admin']) {
			$admin = $request->data['admin'];
		}		
		$company->company_name = $request->data['company_name'];
		$company->email = $request->data['email'];
		$company->phone = $request->data['phone'];
		$company->admin = $admin;
		$company->address = $request->data['address'];
		$company->save();
		return $company;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Company  $company
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Company $company) {
		//
	}

	public function getCompanies() {
		return json_decode(json_encode(Company::all()), true);
	}
	public function getCompanyAdmin() {
		$userRoles = User::with(['roles'])->get();
		$user = [];
		$IdArr = [];
		foreach ($userRoles as $value) {
			foreach ($value->roles as $element) {
				$role_name = $element->name;
				$role_id = $element->id;
				if ($role_name == 'companyAdmin') {
					$user[] = $value;
				}
			}
		}
		return $user;
	}

	public function logo(Request $request, Company $company)
	{
		// return $request->all();
		$upload = Company::find($request->id);
		if ($request->hasFile('image')) {
			$imagename = time() . $request->image->getClientOriginalName();
			$request->image->storeAs('public/logo', $imagename);
			// return response();
		}
		$image_name = '/storage/logo/' . $imagename;
		$upload->logo = $image_name;
		$upload->save();
	}

	public function getLogo()
	{
		return Company::where('id', Auth::user()->branch_id)->get();
	}



	public function getLogoOnly()
	{
		$companies = Company::where('id', Auth::user()->branch_id)->get();
		foreach ($companies as $company) {
			$company_logo = $company->logo;
		}
		return $company_logo;
	}
}
