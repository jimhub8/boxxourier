<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('receipts', function (Blueprint $table) {
			$table->increments('id');
			$table->string('receipt_no');
			$table->date('receipt_date');
			$table->date('due_date');
			$table->string('title');
			$table->string('client')->nullable();
			$table->string('client_address')->nullable();
			$table->decimal('sub_total');
			$table->decimal('discount');
			$table->decimal('grand_total');
			$table->decimal('vat');
			$table->string('currency');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('receipts');
	}
}
