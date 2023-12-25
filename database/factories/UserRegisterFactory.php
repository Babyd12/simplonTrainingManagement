<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserRegisterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = ['user', 'candidat', 'apprenant', 'admin'];
        return [
            'name' => fake()->name(),
            'firstName' => fake()->name(),
            'email' => 'user@gmail.com',
            'email_verified_at' => now(),
            'residence' => fake()->name(),
            'levelOfStudy' => 'Bacalaureat',
            'status' => 'user',
            'password' => Hash::make('password'),
        ];
    }
}
