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
        return [
            'public_id' => Str::uuid(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => 'official_team',
            'contact_info' => fake()->phoneNumber(),
            'profile_picture_path' => null,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
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

    /**
     * Indicate that the model's email address should be verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Create an admin user with admin record.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ])->afterCreating(fn ($user) => $user->admin()->create());
    }

    /**
     * Create a judge user with judge record.
     */
    public function judge(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'judge',
        ])->afterCreating(fn ($user) => $user->judge()->create());
    }

    /**
     * Create a committee user with committee record.
     */
    public function committee(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'commitee',
        ])->afterCreating(fn ($user) => $user->committee()->create(['department' => 'General']));
    }

    /**
     * Create an official team user with official team record.
     */
    public function officialTeam(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'official_team',
        ])->afterCreating(fn ($user) => $user->officialTeam()->create([
            'institution' => 'SMA Test',
            'level' => 'SMA',
            'province' => 'Jakarta',
            'city' => 'Jakarta Selatan',
        ]));
    }
}
