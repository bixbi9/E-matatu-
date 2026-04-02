<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FleetReferenceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('role')->upsert([
            [
                'role_id' => 'DRV',
                'description' => 'Driver',
                'status' => 'Active',
            ],
            [
                'role_id' => 'INSP',
                'description' => 'Inspector',
                'status' => 'Active',
            ],
            [
                'role_id' => 'MGR',
                'description' => 'Manager',
                'status' => 'Active',
            ],
        ], ['role_id'], ['description', 'status']);

        DB::table('drivers')->upsert([
            [
                'driver_id' => 201,
                'first_name' => 'Kevin',
                'last_name' => 'Otieno',
                'license_number' => 'DL-90021',
                'phone_number' => '+254700111222',
                'password' => bcrypt('password'),
                'status' => 'Active',
                'comments' => 'Assigned to Ngong route.',
                'role_id' => 'DRV',
            ],
            [
                'driver_id' => 202,
                'first_name' => 'Mercy',
                'last_name' => 'Wanjiru',
                'license_number' => 'DL-90022',
                'phone_number' => '+254700111333',
                'password' => bcrypt('password'),
                'status' => 'Active',
                'comments' => 'Handles airport early shift.',
                'role_id' => 'DRV',
            ],
            [
                'driver_id' => 203,
                'first_name' => 'Brian',
                'last_name' => 'Kamau',
                'license_number' => 'DL-90023',
                'phone_number' => '+254700111444',
                'password' => bcrypt('password'),
                'status' => 'Off Duty',
                'comments' => 'Available for relief assignments.',
                'role_id' => 'DRV',
            ],
        ], ['driver_id'], ['first_name', 'last_name', 'license_number', 'phone_number', 'password', 'status', 'comments', 'role_id']);

        DB::table('inspectors')->upsert([
            [
                'goverment_id' => 301,
                'first_name' => 'Alice',
                'last_name' => 'Mwangi',
                'role_id' => 'INSP',
                'phone_number' => '+254711000101',
                'password' => bcrypt('password'),
                'status' => 'Active',
            ],
            [
                'goverment_id' => 302,
                'first_name' => 'David',
                'last_name' => 'Kariuki',
                'role_id' => 'INSP',
                'phone_number' => '+254711000102',
                'password' => bcrypt('password'),
                'status' => 'Active',
            ],
        ], ['goverment_id'], ['first_name', 'last_name', 'role_id', 'phone_number', 'password', 'status']);

        DB::table('managers')->upsert([
            [
                'manager_id' => 401,
                'first_name' => 'Janet',
                'last_name' => 'Njeri',
                'role_id' => 'MGR',
                'phone_number' => '+254722300100',
                'password' => bcrypt('password'),
                'status' => 'Active',
                'comments' => 'Oversees CBD operations.',
            ],
            [
                'manager_id' => 402,
                'first_name' => 'Paul',
                'last_name' => 'Omondi',
                'role_id' => 'MGR',
                'phone_number' => '+254722300101',
                'password' => bcrypt('password'),
                'status' => 'Active',
                'comments' => 'Handles fleet compliance.',
            ],
        ], ['manager_id'], ['first_name', 'last_name', 'role_id', 'phone_number', 'password', 'status', 'comments']);

        DB::table('routes')->upsert([
            [
                'route_id' => 501,
                'route_code' => '111',
                'route_name' => 'Ngong Road - CBD',
                'start_location' => 'Ngong Road',
                'end_location' => 'CBD',
                'distance' => 13.5,
                'estimated_time' => '00:50:00',
                'status' => 'Active',
                'driver_id' => 201,
                'source_label' => 'Digital Matatus',
            ],
            [
                'route_id' => 502,
                'route_code' => '34J',
                'route_name' => 'JKIA - CBD',
                'start_location' => 'JKIA',
                'end_location' => 'CBD',
                'distance' => 18.2,
                'estimated_time' => '01:05:00',
                'status' => 'Active',
                'driver_id' => 202,
                'source_label' => 'Digital Matatus',
            ],
            [
                'route_id' => 503,
                'route_code' => '23W',
                'route_name' => 'Westlands - CBD',
                'start_location' => 'Westlands',
                'end_location' => 'CBD',
                'distance' => 8.1,
                'estimated_time' => '00:35:00',
                'status' => 'Standby',
                'driver_id' => 203,
                'source_label' => 'Digital Matatus',
            ],
        ], ['route_id'], ['route_code', 'route_name', 'start_location', 'end_location', 'distance', 'estimated_time', 'status', 'driver_id', 'source_label']);

        DB::table('vehicles')->upsert([
            [
                'vehicle_id' => 101,
                'license_plate' => 'KDA 245A',
                'maintenance_status' => 'Good',
                'inspection_date' => '2024-06-25',
                'vin' => '1HGBH41JXMN109186',
                'color' => 'White',
                'status' => 'Active',
                'current_driver_id' => 201,
                'route_id' => 501,
            ],
            [
                'vehicle_id' => 102,
                'license_plate' => 'KDB 670M',
                'maintenance_status' => 'Needs Service',
                'inspection_date' => '2024-06-26',
                'vin' => '1HGBH41JXMN109187',
                'color' => 'Blue',
                'status' => 'Maintenance',
                'current_driver_id' => 202,
                'route_id' => 502,
            ],
            [
                'vehicle_id' => 103,
                'license_plate' => 'KDC 118Q',
                'maintenance_status' => 'Good',
                'inspection_date' => '2024-06-27',
                'vin' => '1HGBH41JXMN109188',
                'color' => 'Silver',
                'status' => 'Standby',
                'current_driver_id' => 203,
                'route_id' => 503,
            ],
        ], ['vehicle_id'], ['license_plate', 'maintenance_status', 'inspection_date', 'vin', 'color', 'status', 'current_driver_id', 'route_id']);
    }
}
