<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->bigIncrements('driver_id');
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('license_number', 20)->nullable();
            $table->string('phone_number', 15)->nullable();
            $table->string('password', 100)->nullable();
            $table->string('status', 20)->nullable();
            $table->string('comments', 100)->nullable();
            $table->string('role_id', 20)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('drivers');
    }
}
