<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InsuranceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('insurance')->insert([
            [
                'insurance_id' => 1,
                'vehicle_id' => 101,
                'policy_number' => 'POL123456',
                'provider' => 'Insurance Co.',
                'start_date' => '2024-01-01',
                'expiry_date' => '2025-01-01',
                'coverage_details' => 'Full coverage',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 2,
                'vehicle_id' => 102,
                'policy_number' => 'POL654321',
                'provider' => 'Secure Life',
                'start_date' => '2024-02-01',
                'expiry_date' => '2025-02-01',
                'coverage_details' => 'Liability only',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more seed data as needed
        ]);
    }
}
