<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_statuses', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id');
			$table->integer('branch_id');
			$table->integer('shipment_id');
			$table->string('status');
			$table->string('location')->nullable();
			$table->string('geo_location')->nullable();
			$table->string('lat')->nullable();
			$table->string('lng')->nullable();
			$table->string('ip')->nullable();
			$table->string('state_name')->nullable();
			$table->string('country')->nullable();
			$table->string('state')->nullable();
			$table->string('city')->nullable();
			$table->text('remark')->nullable();
			$table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipment_statuses');
    }
}
