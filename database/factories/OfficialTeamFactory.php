<?php

namespace Database\Factories;

use App\Models\OfficialTeam;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OfficialTeam>
 */
class OfficialTeamFactory extends Factory
{
    protected $model = OfficialTeam::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'province' => fake()->country(),
            'city' => fake()->city(),
            'level' => fake()->randomElement(['SMP/MTs Sederajat', 'SMA/SMK/MA Sederajat', 'SD/MI Sederajat', 'Purna/Umum']),
            'institution' => fake()->company(),
        ];
    }
}
