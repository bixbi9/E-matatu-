<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (DB::getDefaultConnection() !== 'supabase') {
            User::factory(10)->create();
        }

        // User::factory()->create([
        //     '    name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            FleetReferenceSeeder::class,
            InspectionsTableSeeder::class,
            MaintenanceTableSeeder::class,
            InsuranceTableSeeder::class,
        ]);
    }

}
