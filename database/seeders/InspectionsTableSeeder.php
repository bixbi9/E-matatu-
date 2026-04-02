<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InspectionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('inspections')->upsert([
            [
                'inspection_id'    => 1,
                'vehicle_id'       => 101,
                'inspector_name'   => 'Alice Mwangi',
                'result'           => 'Pass',
                'comments'         => 'Vehicle in excellent condition. Lights, brakes and tyres all within NTSA standards.',
                'rating'           => 'A',
                'status'           => 'Completed',
                'inspection_date'  => '2024-06-25',
                'evaluation_form'  => 'All NTSA roadworthiness criteria met.',
                'maintenance_type' => 'Routine',
            ],
            [
                'inspection_id'    => 2,
                'vehicle_id'       => 102,
                'inspector_name'   => 'David Kariuki',
                'result'           => 'Fail',
                'comments'         => 'Rear brake pads worn below minimum. Windscreen wiper ineffective. Requires immediate service.',
                'rating'           => 'C',
                'status'           => 'Pending',
                'inspection_date'  => '2024-06-26',
                'evaluation_form'  => 'Brake system and wipers fail NTSA inspection criteria.',
                'maintenance_type' => 'Urgent',
            ],
            [
                'inspection_id'    => 3,
                'vehicle_id'       => 103,
                'inspector_name'   => 'Alice Mwangi',
                'result'           => 'Pass',
                'comments'         => 'Minor tyre wear noted but within limits. All safety equipment present and functional.',
                'rating'           => 'B',
                'status'           => 'Completed',
                'inspection_date'  => '2024-06-27',
                'evaluation_form'  => 'NTSA inspection passed with advisory on tyre replacement within 3 months.',
                'maintenance_type' => 'Routine',
            ],
        ], ['inspection_id'], ['vehicle_id', 'inspector_name', 'result', 'comments', 'rating', 'status', 'inspection_date', 'evaluation_form', 'maintenance_type']);
    }
}
