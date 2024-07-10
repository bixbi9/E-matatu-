<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('vehicle_id');
            $table->string('license_plate', 15)->nullable();
            $table->string('maintenance_status', 15)->nullable();
            $table->date('inspection_date')->nullable();
            $table->string('vin', 17)->nullable();
            $table->string('color', 30)->nullable();
            $table->string('status', 20)->nullable();
            $table->bigInteger('current_driver_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}


