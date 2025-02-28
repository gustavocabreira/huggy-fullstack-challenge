<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'date_of_birth' => now()->subDay()->format('Y-m-d'),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => (int) Str::repeat('9', 10),
            'cellphone_number' => (int) Str::repeat('9', 11),
            'address' => fake()->address(),
            'district' => fake()->state(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zip_code' => fake()->postcode(),
            // 'photo' => fake()->imageUrl(),
        ];
    }
}
