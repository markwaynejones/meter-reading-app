<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meter>
 */
class MeterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mpxn' => $this->faker->unique()->regexify('[A-Z0-9]{10}'),
            'serial_number' => null,
            'type' => $this->faker->randomElement(['gas', 'electric']),
            'installation_date' => $this->faker->dateTimeBetween('-5 years')->format('Y-m-d'), // date within the last 5 years
            'estimated_annual_consumption' => $this->faker->numberBetween(2000, 8000),
        ];
    }
}
