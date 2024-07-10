<?php

namespace Database\Factories;

use App\Models\Maintenance;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceFactory extends Factory
{
    protected $model = Maintenance::class;

    public function definition()
    {
        return [
            'vehicle_id' => $this->faker->numberBetween(1, 100),
            'date' => $this->faker->date,
            'description' => $this->faker->sentence,
            'cost' => $this->faker->randomFloat(2, 10, 500),
            'maintenance_type' => $this->faker->randomElement(['Routine', 'Urgent']),
            'status' => $this->faker->randomElement(['Completed', 'Pending']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
