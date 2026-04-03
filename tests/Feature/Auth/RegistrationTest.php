<?php

use App\Models\OfficialTeam;
use App\Models\User;

describe('Registration Form', function () {
    test('registration screen can be rendered', function () {
        $response = $this->get(route('register'));
        $response->assertOk();
    });
});

describe('Official Team Registration', function () {
    test('new official team users can register with all required fields', function () {
        $registrationData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'institution' => 'SMA ABC',
            'level' => 'SMA',
            'province' => 'Jakarta',
            'city' => 'Jakarta Selatan',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ];

        $response = $this->post(route('register.store'), $registrationData);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    });

    test('user is created with official_team role', function () {
        $response = $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'institution' => 'SMA ABC',
            'level' => 'SMA',
            'province' => 'Jakarta',
            'city' => 'Jakarta Selatan',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        expect($user)->not->toBeNull();
        expect($user->role)->toBe('official_team');
    });

    test('official_team record is created with all fields', function () {
        $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'institution' => 'SMA ABC',
            'level' => 'SMA',
            'province' => 'Jakarta',
            'city' => 'Jakarta Selatan',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $team = $user->officialTeam;

        expect($team)->not->toBeNull();
        expect($team->institution)->toBe('SMA ABC');
        expect($team->level)->toBe('SMA');
        expect($team->province)->toBe('Jakarta');
        expect($team->city)->toBe('Jakarta Selatan');
    });

    test('user must provide uuid as public_id', function () {
        $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'institution' => 'SMA ABC',
            'level' => 'SMA',
            'province' => 'Jakarta',
            'city' => 'Jakarta Selatan',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        expect($user->public_id)->not->toBeNull();
        expect($user->public_id)->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i');
    });
});

describe('Registration Validation', function () {
    test('name is required', function () {
        $response = $this->post(route('register.store'), [
            'email' => 'test@example.com',
            'institution' => 'SMA ABC',
            'level' => 'SMA',
            'province' => 'Jakarta',
            'city' => 'Jakarta Selatan',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('name');
        expect(User::where('email', 'test@example.com')->exists())->toBeFalse();
    });

    test('email is required and must be unique', function () {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'institution' => 'SMA ABC',
            'level' => 'SMA',
            'province' => 'Jakarta',
            'city' => 'Jakarta Selatan',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
    });

    test('institution is required', function () {
        $response = $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'level' => 'SMA',
            'province' => 'Jakarta',
            'city' => 'Jakarta Selatan',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('institution');
    });

    test('level is required', function () {
        $response = $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'institution' => 'SMA ABC',
            'province' => 'Jakarta',
            'city' => 'Jakarta Selatan',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('level');
    });

    test('province is required', function () {
        $response = $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'institution' => 'SMA ABC',
            'level' => 'SMA',
            'city' => 'Jakarta Selatan',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('province');
    });

    test('city is required', function () {
        $response = $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'institution' => 'SMA ABC',
            'level' => 'SMA',
            'province' => 'Jakarta',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('city');
    });

    test('password is required and must be confirmed', function () {
        $response = $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'institution' => 'SMA ABC',
            'level' => 'SMA',
            'province' => 'Jakarta',
            'city' => 'Jakarta Selatan',
            'password' => 'Password123!',
            'password_confirmation' => 'WrongPassword123!',
        ]);

        $response->assertSessionHasErrors('password');
    });

    test('password must meet security requirements', function () {
        $response = $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'institution' => 'SMA ABC',
            'level' => 'SMA',
            'province' => 'Jakarta',
            'city' => 'Jakarta Selatan',
            'password' => 'weak',
            'password_confirmation' => 'weak',
        ]);

        $response->assertSessionHasErrors('password');
    });
});