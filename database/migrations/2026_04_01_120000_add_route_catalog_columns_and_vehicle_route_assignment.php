<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            if (! Schema::hasColumn('routes', 'route_code')) {
                $table->string('route_code', 30)->nullable()->after('route_id');
            }

            if (! Schema::hasColumn('routes', 'route_name')) {
                $table->string('route_name', 120)->nullable()->after('route_code');
            }

            if (! Schema::hasColumn('routes', 'source_label')) {
                $table->string('source_label', 120)->nullable()->after('driver_id');
            }
        });

        Schema::table('vehicles', function (Blueprint $table) {
            if (! Schema::hasColumn('vehicles', 'route_id')) {
                $table->unsignedBigInteger('route_id')->nullable()->after('current_driver_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('vehicles', 'route_id')) {
                $table->dropColumn('route_id');
            }
        });

        Schema::table('routes', function (Blueprint $table) {
            $columns = ['route_code', 'route_name', 'source_label'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('routes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
