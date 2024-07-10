<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->bigIncrements('route_id');
            $table->string('start_location', 100)->nullable();
            $table->string('end_location', 100)->nullable();
            $table->float('distance')->nullable();
            $table->time('estimated_time')->nullable();
            $table->string('status', 20)->nullable();
            $table->bigInteger('driver_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('routes');
    }
}

