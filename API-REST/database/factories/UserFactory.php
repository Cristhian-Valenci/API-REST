<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('Password123.'), 
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Usuario admin
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('Password123.'), 
        ]);
    }

    /**
     * Usuario verificado normal
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Verified User',
            'email' => 'verified@example.com',
            'password' => Hash::make('Password123.'), 
        ]);
    }

    /**
     * Email sin verificar
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}