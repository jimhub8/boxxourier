<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $fillable = [
		'charge', 'town_id', 'town', 'user_id', 'total', 'vat',
	];
    public function town()
    {
        return $this->belongsTo('App\Town');
    }
}
