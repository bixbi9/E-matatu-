<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InsuranceTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('insurance')->upsert([
            [
                'insurance_id'     => 1,
                'vehicle_id'       => 101,
                'policy_number'    => 'JUB/PSV/2024/00437',
                'provider'         => 'Jubilee Insurance Kenya',
                'start_date'       => '2024-01-15',
                'expiry_date'      => '2025-01-14',
                'coverage_details' => 'PSV Comprehensive Cover — third-party liability, passenger injury, fire and theft. Sum insured KES 1,200,000.',
            ],
            [
                'insurance_id'     => 2,
                'vehicle_id'       => 102,
                'policy_number'    => 'APA/PSV/2024/00821',
                'provider'         => 'APA Insurance Kenya',
                'start_date'       => '2024-02-01',
                'expiry_date'      => '2025-01-31',
                'coverage_details' => 'PSV Third Party Only — statutory third-party liability per Traffic Act Cap 403. Sum insured KES 500,000.',
            ],
            [
                'insurance_id'     => 3,
                'vehicle_id'       => 103,
                'policy_number'    => 'CIC/PSV/2024/01155',
                'provider'         => 'CIC Insurance Group',
                'start_date'       => '2024-03-01',
                'expiry_date'      => '2025-02-28',
                'coverage_details' => 'PSV Comprehensive Cover — third-party liability, passenger injury, fire and theft. Sum insured KES 950,000.',
            ],
        ], ['insurance_id'], ['vehicle_id', 'policy_number', 'provider', 'start_date', 'expiry_date', 'coverage_details']);
    }
}
