<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleTable extends Migration
{
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->string('role_id', 20)->primary();
            $table->string('description', 20)->nullable();
            $table->string('status', 20)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('role');
    }
}

