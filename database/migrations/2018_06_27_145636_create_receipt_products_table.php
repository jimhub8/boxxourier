<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptProductsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('receipt_products', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('receipt_id')->unsigned();
			$table->string('name');
			$table->integer('qty');
			$table->decimal('price');
			$table->decimal('total');
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
		Schema::dropIfExists('receipt_products');
	}
}
