<?php

namespace Database\Factories;

use App\Models\Inspection;
use App\Models\Inspections;
use Illuminate\Database\Eloquent\Factories\Factory;

class InspectionFactory extends Factory
{
    protected $model = Inspections::class;

    public function definition()
    {
        return [
            'vehicle_id' => $this->faker->numberBetween(1, 100),
            'inspector_name' => $this->faker->name,
            'result' => $this->faker->randomElement(['Pass', 'Fail']),
            'comments' => $this->faker->sentence,
            'rating' => $this->faker->randomElement(['A', 'B', 'C']),
            'status' => $this->faker->randomElement(['Completed', 'Pending']),
            'inspection_date' => $this->faker->date,
            'evaluation_form' => $this->faker->paragraph,
            'maintenance_type' => $this->faker->randomElement(['Routine', 'Urgent']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
