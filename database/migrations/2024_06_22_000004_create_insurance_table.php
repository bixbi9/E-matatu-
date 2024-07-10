<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceTable extends Migration
{
    public function up()
    {
        Schema::create('insurance', function (Blueprint $table) {
            $table->bigIncrements('insurance_id');
            $table->bigInteger('vehicle_id')->nullable();
            $table->string('policy_number', 50)->nullable();
            $table->string('provider', 50)->nullable();
            $table->date('start_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('coverage_details')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('insurance');
    }
}
