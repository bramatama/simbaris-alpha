<?php

use App\Models\User;

describe('Inertia Auth Shared Props', function () {
    test('verified admin user can access dashboard', function () {
        $admin = User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        // Dashboard returns OK response
        $response->assertOk();
    });

    test('verified judge user can access dashboard', function () {
        $judge = User::factory()->judge()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($judge)->get(route('dashboard'));

        $response->assertOk();
    });

    test('verified committee user can access dashboard', function () {
        $committee = User::factory()->committee()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($committee)->get(route('dashboard'));

        $response->assertOk();
    });

    test('verified official_team user can access dashboard', function () {
        $officialTeam = User::factory()->officialTeam()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($officialTeam)->get(route('dashboard'));

        $response->assertOk();
    });

    test('unverified user redirected to verify email', function () {
        $user = User::factory()->create(['email_verified_at' => null]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('verification.notice')
        );
    });
});