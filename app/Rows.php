<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rows extends Model
{
    protected $fillable = [
		'text', 'user_id'
	];
    public function users()
    {
        return $this->hasMany('App\User');
    }
}
