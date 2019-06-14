<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model {
	use SoftDeletes;
	protected $fillable = [
		'receipt_no', 'receipt_date', 'due_date',
		'title', 'sub_total', 'discount',
		'grand_total', 'client',
		'client_address', 'invoice_id', 'vat'
	];

	public function products() {
		return $this->hasMany(ReceiptProduct::class);
	}

	public function invoice() {
		return $this->belongsTo(Invoice::class);
	}

}
