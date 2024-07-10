<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInspectionsTable extends Migration
{
    public function up()
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->bigIncrements('inspection_id');
            $table->bigInteger('vehicle_id')->nullable();
            $table->string('inspector_name', 50)->nullable();
            $table->string('result', 20)->nullable();
            $table->text('comments')->nullable();
            $table->string('rating', 50)->nullable();
            $table->string('status', 50)->nullable();
            $table->date('inspection_date')->nullable();
            $table->text('evaluation_form')->nullable();
            $table->string('maintenance_type', 50)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inspections');
    }
}
