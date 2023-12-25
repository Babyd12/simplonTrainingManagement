<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = ['user', 'candidat', 'apprenant', 'admin'];
        $levelOfStudy = ['Bacalaureat', 'Licence 1', 'Master 2', 'Master 1'];
        return [
            'name' => fake()->name(),
            'firstName' => fake()->name(),
            'email' => fake()->email(),
            'email_verified_at' => now(),
            'residence' => fake()->name(),
            'levelOfStudy' => $levelOfStudy[mt_rand(0,3)],
            'status' => $status[mt_rand(0, 3)],
            'password' => static::$password ??= Hash::make('password'),
        ];
    }

     /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


   

   public function admin() : UserFactory
   {
    return $this->state([
        'name' => $this->faker->name,
        'firstName' => $this->faker->firstName,
        'email' => 'admin@admin.com',
        'email_verified_at' => now(),
        'residence' => $this->faker->name,
        'levelOfStudy' => $this->faker->randomElement(['Bacalaureat', 'Licence 1', 'Master 2', 'Master 1']),
        'status' => 'admin',
        'password' => static::$password ??= Hash::make('password'),
    ]);
   }

   public function user() : UserFactory
   {
    return $this->state([
        'name' => $this->faker->name,
        'firstName' => $this->faker->firstName,
        'email' => fake()->email(),
        'email_verified_at' => now(),
        'residence' => $this->faker->name,
        'levelOfStudy' => $this->faker->randomElement(['Bacalaureat', 'Licence 1', 'Master 2', 'Master 1']),
        'status' => 'user',
        'password' => static::$password ??= Hash::make('password'),
    ]);
   }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
