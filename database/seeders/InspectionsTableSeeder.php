<?php

namespace Database\Seeders;

use App\Models\Inspections;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InspectionsTableSeeder extends Seeder
{
   /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('inspections')->insert([
            [
                'inspection_id' => 1,
                'vehicle_id' => 101,
                'inspector_name' => 'John Doe',
                'result' => 'Pass',
                'comments' => 'No issues found.',
                'rating' => 'A',
                'status' => 'Completed',
                'inspection_date' => '2024-06-25',
                'evaluation_form' => 'All criteria met.',
                'maintenance_type' => 'Routine',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'inspection_id' => 2,
                'vehicle_id' => 102,
                'inspector_name' => 'Jane Smith',
                'result' => 'Fail',
                'comments' => 'Brake issues.',
                'rating' => 'C',
                'status' => 'Pending',
                'inspection_date' => '2024-06-26',
                'evaluation_form' => 'Brake issues need addressing.',
                'maintenance_type' => 'Urgent',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more seed data as needed
        ]);
    }
}
