<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('invoices', function (Blueprint $table) {
			$table->increments('id');
			$table->string('invoice_no');
			$table->date('invoice_date');
			$table->date('due_date');
			$table->string('title');
			$table->string('client')->nullable();
			$table->integer('client_id');
			$table->string('client_address')->nullable();
			$table->decimal('sub_total');
			$table->decimal('discount');
			$table->decimal('grand_total');
			$table->decimal('balance')->nullable();
			$table->decimal('vat');
			$table->string('currency');
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('invoices');
	}
}
