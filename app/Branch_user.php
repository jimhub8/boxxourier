<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch_user extends Model {
	use SoftDeletes;
	protected $table = 'branch_user';
	protected $fillable = [
		'user_id', 'branch_id',
	];
}
