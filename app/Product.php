<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use SoftDeletes;
    public function shipment()
    {
        return $this->belongsTo('App\Shipment');
    }
    public $fillable = [
        'product_name', 'weight', 'price', 'total', 'quantity', 'shipments_id', 'user_id'
    ];
}
