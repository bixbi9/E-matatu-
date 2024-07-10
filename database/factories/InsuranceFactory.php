<?php

namespace Database\Factories;

use App\Models\Insurance;
use Illuminate\Database\Eloquent\Factories\Factory;

class InsuranceFactory extends Factory
{
    protected $model = Insurance::class;

    public function definition()
    {
        return [
            'vehicle_id' => $this->faker->numberBetween(1, 100),
            'policy_number' => $this->faker->regexify('[A-Z0-9]{10}'),
            'provider' => $this->faker->company,
            'start_date' => $this->faker->date,
            'expiry_date' => $this->faker->date,
            'coverage_details' => $this->faker->paragraph,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
