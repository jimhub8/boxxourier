<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
	use SoftDeletes;

	public function users()
	{
		return $this->hasMany('App\User', 'branch_id');
	}
	public function shipments()
	{
		return $this->hasMany('App\Shipment', 'branch_id');
	}
	public function branches()
	{
		return $this->hasMany('App\Branch', 'country_id');
	}
}
