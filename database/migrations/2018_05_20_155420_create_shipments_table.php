<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('shipments', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->string('sender_name')->nullable();
			$table->string('sender_email')->nullable();
			$table->string('sender_address')->nullable();
			$table->string('sender_city')->nullable();
			$table->string('sender_phone')->nullable();
			$table->string('client_name')->nullable();
			$table->string('client_email')->nullable();
			$table->string('client_address')->nullable();
			$table->string('client_postal_code')->nullable();
			$table->string('client_region')->nullable();
			$table->string('client_city')->nullable();
			$table->string('client_phone')->nullable();
			$table->integer('client_id')->nullable();
			$table->string('assign_staff')->nullable();
			$table->string('airway_bill_no')->nullable();
			$table->string('bar_code')->nullable();
			$table->integer('amount_ordered')->nullable();
			$table->integer('shipment_id');
			$table->boolean('paid')->nullable();
			$table->string('status')->nullable();
			$table->string('container')->nullable();
			$table->string('driver')->nullable();
			$table->string('payment')->nullable();
			$table->string('shipment_type')->nullable();
			$table->string('insuarance_status')->nullable();
			$table->string('from')->nullable();
			$table->string('to')->nullable();
			$table->string('charges')->nullable();
			$table->date('booking_date')->nullable();
			$table->date('derivery_date')->nullable();
			$table->time('derivery_time')->nullable();
			$table->text('remark')->nullable();
			$table->integer('branch_id')->nullable();
			$table->string('derivery_status')->nullable();
			$table->string('order_id')->nullable();
			$table->string('to_city')->nullable();
			$table->string('from_city')->nullable();
			$table->string('weight')->nullable();
			$table->string('receiver_name')->nullable();
			$table->decimal('sub_total')->nullable();
			$table->decimal('cod_amount')->nullable();
			$table->text('speciial_instruction')->nullable();
			$table->string('lat_to')->nullable();
			$table->string('lat')->nullable();
			$table->string('lng')->nullable();
			$table->string('location')->nullable();
			$table->string('lng_to')->nullable();
			$table->integer('printed')->nullable();
			$table->text('description')->nullable();
			$table->text('country')->nullable();
			$table->integer('country_id')->nullable();
			$table->double('distance', 5, 2)->nullable();
			$table->boolean('printReceipt')->nullable();
			$table->date('assign_date')->nullable();
			$table->boolean('sticker')->default(0);
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
		Schema::dropIfExists('shipments');
	}
}


// INSERT INTO `shipments` (`id`, `user_id`, `sender_name`, `sender_email`, `sender_address`, `sender_city`, `sender_phone`, `client_name`, `client_email`, `client_address`, `client_postal_code`, `client_region`, `client_city`, `client_phone`, `client_id`, `assign_staff`, `airway_bill_no`, `bar_code`, `amount_ordered`, `shipment_id`, `paid`, `status`, `container`, `driver`, `payment`, `shipment_type`, `insuarance_status`, `from`, `to`, `charges`, `booking_date`, `derivery_date`, `derivery_time`, `remark`, `branch_id`, `derivery_status`, `order_id`, `to_city`, `from_city`, `weight`, `receiver_name`, `sub_total`, `cod_amount`, `speciial_instruction`, `description`, `deleted_at`, `created_at`, `updated_at`, `lat_to`, `lng_to`, `lat`, `lng`, `location`, `printed`, `country`, `printReceipt`) VALUES (NULL, '1', 'speedballcourier', 'info@speedballcourier.com', '17254 00100', 'Nairobi', NULL, 'sylvia Mbugua', 'elizabeth.ormidale@aol.co.uk', 'Imara Daima Estate Fig tree court 414	', NULL, NULL, 'Nairobi', '0722158005 ', '100', NULL, '6359 ', '6359 ', '1', '992992', '0', 'warehouse', NULL, NULL, NULL, NULL, NULL, 'speedballcourier', NULL, NULL, '2018-10-05', '2018-10-06', NULL, NULL, '1', NULL, NULL, 'Nairobi', 'Nairobi', NULL, NULL, NULL, '0', 'Pickup and Drop off for Orders to Jumia Hubs - South B to Road C', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', 'Kenya', '0');