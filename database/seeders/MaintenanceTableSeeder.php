<?php

namespace Database\Seeders;

use App\Models\Maintenance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaintenanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     
    public function run()
    {
        Maintenance::table('maintenance')->insert([
            [
                'maintenance_id' => 1,
                'vehicle_id' => 101,
                'date' => '2024-06-25',
                'description' => 'Oil change',
                'cost' => 50.0,
                'maintenance_type' => 'Routine',
                'status' => 'Completed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'maintenance_id' => 2,
                'vehicle_id' => 102,
                'date' => '2024-06-26',
                'description' => 'Brake replacement',
                'cost' => 200.0,
                'maintenance_type' => 'Urgent',
                'status' => 'Pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more seed data as needed

            
        ]);
    }
}
