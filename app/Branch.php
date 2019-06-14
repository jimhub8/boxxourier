<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
	use SoftDeletes;
	// public $with = ['users'];
    protected $fillable = [
		'branch_name', 'email', 'phone', 'address', 'country_id', 'user_id', 'branch_id'
	];
	/**
	 * The users that belong to the role.
	 */
	public function users() {
		return $this->hasMany('App\User','branch_id');
	}

	public function shipments() {
		return $this->hasMany('App\Shipment','branch_id');
	}
	
	public function country()
	{
		return $this->belongsTo('App\Country', 'country_id');
	}
}
