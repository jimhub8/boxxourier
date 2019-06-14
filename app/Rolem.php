<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rolem extends Model {
	protected $table = 'role';

	protected $fillable = [
		'name', 'description',
	];
	/**
	 * The users that belong to the role.
	 */
	// public function users() {
	// 	return $this->belongsToMany('App\User');
	// }
}
