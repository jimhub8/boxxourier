<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
	public function charges() {
		return $this->hasMany('App\Charge','town_id');
	}
}
