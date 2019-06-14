<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    public function users()
    {
        return $this->hasMany('App\User', 'user_id');
    }
    public function shipments()
    {
        return $this->hasMany('App\Shipment', 'user_id');
    }
}
