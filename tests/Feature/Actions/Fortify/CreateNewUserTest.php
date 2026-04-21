<?php

use App\Models\User;

describe('CreateNewUser Action - Official Team Registration', function () {
    test('guest can create official_team user with valid data', function () {
        $response = $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'institution' => 'SMA ABC',
            'level' => 'SMA',
            'province' => 'Jakarta',
            'city' => 'Jakarta Selatan',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        expect($user)->not->toBeNull();
        expect($user->role)->toBe('official_team');
        expect($user->officialTeam)->not->toBeNull();
        expect($user->officialTeam->institution)->toBe('SMA ABC');
    });

    test('defaults to official_team role for guests', function () {
        $response = $this->post(route('register.store'), [
            'name' => 'Test User2',
            'email' => 'test2@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'institution' => 'SMA DEF',
            'level' => 'SMK',
            'province' => 'Bandung',
            'city' => 'Bandung',
        ]);

        $user = User::where('email', 'test2@example.com')->first();
        expect($user->role)->toBe('official_team');
    });

    test('public_id is valid UUID', function () {
        $this->post(route('register.store'), [
            'name' => 'Test User3',
            'email' => 'test3@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'institution' => 'SMA GHI',
            'level' => 'SMA',
            'province' => 'Surabaya',
            'city' => 'Surabaya',
        ]);

        $user = User::where('email', 'test3@example.com')->first();
        expect($user->public_id)->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i');
    });

    test('email must be unique', function () {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'institution' => 'SMA ABC',
            'level' => 'SMA',
            'province' => 'Jakarta',
            'city' => 'Jakarta Selatan',
        ]);

        $response->assertSessionHasErrors('email');
    });

    test('password must meet security requirements', function () {
        $response = $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'weakpass@example.com',
            'password' => 'weak',
            'password_confirmation' => 'weak',
            'institution' => 'SMA ABC',
            'level' => 'SMA',
            'province' => 'Jakarta',
            'city' => 'Jakarta Selatan',
        ]);

        $response->assertSessionHasErrors('password');
    });

    test('guest cannot create admin user via register endpoint', function () {
        $response = $this->post(route('register.store'), [
            'name' => 'Hacker',
            'email' => 'hacker@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'admin',
        ]);

        expect(User::where('email', 'hacker@example.com')->exists())->toBeFalse();
    });

    test('guest cannot create judge user via register endpoint', function () {
        $response = $this->post(route('register.store'), [
            'name' => 'Hacker',
            'email' => 'hacker2@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'judge',
        ]);

        expect(User::where('email', 'hacker2@example.com')->exists())->toBeFalse();
    });

    test('guest cannot create committee user via register endpoint', function () {
        $response = $this->post(route('register.store'), [
            'name' => 'Hacker',
            'email' => 'hacker3@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'committee',
            'department' => 'Hacking',
        ]);

        expect(User::where('email', 'hacker3@example.com')->exists())->toBeFalse();
    });
});
