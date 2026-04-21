<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

describe('Role-Based Dashboard Assignment', function () {
    test('admin user sees admin dashboard', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('admin/dashboard')
        );
    });

    test('admin user has correct role in shared props', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertInertia(fn ($page) => $page
            ->has('auth.user', fn ($user) => $user
                ->where('role', 'admin')
                ->etc()
            )
        );
    });

    test('judge user sees judge dashboard', function () {
        $judge = User::factory()->judge()->create();

        $response = $this->actingAs($judge)->get(route('dashboard'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('judge/dashboard')
        );
    });

    test('judge user has correct role in shared props', function () {
        $judge = User::factory()->judge()->create();

        $response = $this->actingAs($judge)->get(route('dashboard'));

        $response->assertInertia(fn ($page) => $page
            ->has('auth.user', fn ($user) => $user
                ->where('role', 'judge')
                ->etc()
            )
        );
    });

    test('committee user sees committee dashboard', function () {
        $committee = User::factory()->committee()->create();

        $response = $this->actingAs($committee)->get(route('dashboard'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('committee/dashboard')
        );
    });

    test('committee user has correct role in shared props', function () {
        $committee = User::factory()->committee()->create();

        $response = $this->actingAs($committee)->get(route('dashboard'));

        $response->assertInertia(fn ($page) => $page
            ->has('auth.user', fn ($user) => $user
                ->where('role', 'commitee')
                ->etc()
            )
        );
    });

    test('official team user sees official team dashboard', function () {
        $officialTeam = User::factory()->officialTeam()->create();

        $response = $this->actingAs($officialTeam)->get(route('dashboard'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('official_team/dashboard')
        );
    });

    test('official team user has correct role in shared props', function () {
        $officialTeam = User::factory()->officialTeam()->create();

        $response = $this->actingAs($officialTeam)->get(route('dashboard'));

        $response->assertInertia(fn ($page) => $page
            ->has('auth.user', fn ($user) => $user
                ->where('role', 'official_team')
                ->etc()
            )
        );
    });
});