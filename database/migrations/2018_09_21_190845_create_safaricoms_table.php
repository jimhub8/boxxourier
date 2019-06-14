<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSafaricomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('safaricoms', function (Blueprint $table) {
            $table->increments('id');
			$table->string('TransID')->nullable();
			$table->string('TransactionType')->nullable();
			$table->string('TransTime')->nullable();
			$table->string('TransAmount')->nullable();
			$table->string('BusinessShortCode')->nullable();
			$table->string('BillRefNumber')->nullable();
			$table->string('InvoiceNumber')->nullable();
			$table->string('MSISDN')->nullable();
			$table->string('First_Name')->nullable();
			$table->string('Middle_Name')->nullable();
			$table->string('Last_Name')->nullable();
			$table->string('OrgAccountBalance')->nullable();
			$table->string('Request')->nullable();
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
        Schema::dropIfExists('safaricoms');
    }
}
