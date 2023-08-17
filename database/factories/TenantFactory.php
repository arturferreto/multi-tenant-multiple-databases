<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $company = fake()->company(),
            'slug' => Str::slug($company),
            'database' => Str::replace('-', '_', Str::slug($company)),
            'total_users' => fake()->numberBetween(1, 10),
        ];
    }
}
