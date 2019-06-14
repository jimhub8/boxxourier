<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleLogs extends Model
{
	protected $fillable = [
		'user_id', 'count',
	];
}
