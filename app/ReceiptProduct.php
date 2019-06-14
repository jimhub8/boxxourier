<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptProduct extends Model {
	use SoftDeletes;
	protected $fillable = [
		'name', 'price', 'qty', 'total',
	];

	public function receipt() {
		return $this->belongsTo(Receipt::class);
	}
}
