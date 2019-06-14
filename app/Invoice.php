<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model {
	use SoftDeletes;
	public $with = ['products'];
	protected $fillable = [
		'invoice_no', 'invoice_date', 'due_date',
		'title', 'sub_total', 'discount',
		'grand_total', 'client',
		'client_address', 'balance', 'vat', 'currency', 'client_id'
	];

	public function products() {
		return $this->hasMany(InvoiceProduct::class);
	}

	public function buyer() {
		return $this->belongsTo(Buyer::class);
	}

	public function receipts() {
		return $this->hasMany(Receipt::class, 'invoice_id');
	}
}
