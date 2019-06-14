<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model {
	use SoftDeletes;
	protected $fillable = [
		'location', 'company_name', 'email', 'address',
		'phone', 'branches', 'admin', 'user_id',
	];
}
