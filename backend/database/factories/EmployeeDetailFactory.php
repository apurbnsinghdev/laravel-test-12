<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Department;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeDetail>
 */
class EmployeeDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'designation' => $this->faker->jobTitle,
            'salary' => $this->faker->randomFloat(2, 30000, 120000),
            'address' => $this->faker->address,
            'joined_date' => $this->faker->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
