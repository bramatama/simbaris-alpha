<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\OfficialTeam;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $adminUser= User::factory()->create([
            'name' => 'Admin SIMBARIS',
            'email' => 'bramatamar@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'email_verified_at' => Carbon::now(),
        ]);

        $officialTeamUser=User::factory()->create([
            'name' => 'Admin 18',
            'email' => 'paskasspandlas.bpn@gmail.com',
            'password' => bcrypt('test1234'),
            'role' => 'official_team',
            'email_verified_at' => Carbon::now(),
        ]);

        Admin::factory()->create([
            'user_id' => $adminUser->user_id,
        ]);

        OfficialTeam::factory()->create([
            'user_id' => $officialTeamUser->user_id,
            'province' => 'Kalimantan Timur',
            'city' => 'Balikpapan',
            'level' => 'SMP/MTs Sederajat',
            'institution' => 'SMP 18 Balikpapan',
        ]);
    }
}
