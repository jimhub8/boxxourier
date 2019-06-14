<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('companies', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('company_name');
			$table->string('email')->nullable();
			$table->string('phone')->nullable();
			$table->string('address')->nullable();
			$table->string('branches')->nullable();
			$table->string('admin')->nullable();
			$table->string('location')->nullable();
			$table->string('longitude')->nullable();
			$table->string('latitude')->nullable();
			$table->string('country')->nullable();
			$table->string('locality')->nullable();
			// $table->string('route')->nullable();
			// $table->string('street_number')->nullable();
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
		Schema::dropIfExists('companies');
	}
}
