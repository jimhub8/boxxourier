<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rolem_user extends Model {
	use SoftDeletes;
	protected $table = 'role_user';
	protected $fillable = [
		'user_id', 'role_id',
	];
}
