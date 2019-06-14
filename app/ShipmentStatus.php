<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ShipmentStatus extends Model
{
	use SoftDeletes;
    public $fillable = [
        'status', 'user_id', 'branch_id', 'remark'
    ];
    public function shipment()
    {
        return $this->belongsTo('App\Shipment');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function getuserNameAttribute () {
        foreach (ShipmentStatus::all() as $status) {
            if (Auth::user()->can($status->name)) {
                $statuses[$status->name] = true;
            } else {
                $statuses[$status->name] = false;
            }
        }
    }
}
