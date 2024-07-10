<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceTable extends Migration
{
    public function up()
    {
        Schema::create('maintenance', function (Blueprint $table) {
            $table->bigIncrements('maintenance_id');
            $table->bigInteger('vehicle_id')->nullable();
            $table->date('date')->nullable();
            $table->text('description')->nullable();
            $table->float('cost')->nullable();
            $table->string('maintenance_type', 50)->nullable();
            $table->string('status', 20)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenance');
    }
}
