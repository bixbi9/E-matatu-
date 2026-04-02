<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaintenanceTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('maintenance')->upsert([
            [
                'maintenance_id'   => 1,
                'vehicle_id'       => 101,
                'date'             => '2024-06-25',
                'description'      => 'Routine oil change (5W-30 full synthetic) and oil filter replacement. Engine running smoothly post-service.',
                'cost'             => 4500.00,
                'maintenance_type' => 'Routine',
                'status'           => 'Completed',
            ],
            [
                'maintenance_id'   => 2,
                'vehicle_id'       => 102,
                'date'             => '2024-06-26',
                'description'      => 'Replacement of worn rear brake pads and brake fluid flush. Vehicle grounded pending parts delivery from Mombasa Road spares.',
                'cost'             => 18500.00,
                'maintenance_type' => 'Urgent',
                'status'           => 'Pending',
            ],
            [
                'maintenance_id'   => 3,
                'vehicle_id'       => 103,
                'date'             => '2024-06-27',
                'description'      => 'Full tyre rotation and tread depth check. Front left tyre replaced (wore to 2mm). Wheel alignment corrected.',
                'cost'             => 9200.00,
                'maintenance_type' => 'Routine',
                'status'           => 'Completed',
            ],
            [
                'maintenance_id'   => 4,
                'vehicle_id'       => 101,
                'date'             => '2024-07-10',
                'description'      => 'Air filter and fuel filter replacement. Spark plugs checked and one replaced on cylinder 3. Overall engine health good.',
                'cost'             => 6800.00,
                'maintenance_type' => 'Routine',
                'status'           => 'Completed',
            ],
        ], ['maintenance_id'], ['vehicle_id', 'date', 'description', 'cost', 'maintenance_type', 'status']);
    }
}
