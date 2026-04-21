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

describe('Admin Registration', function () {
    test('authenticated admin can access admin registration form', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('register'));

        $response->assertOk();
    });

    test('authenticated admin can register a new admin user', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('register.store'), [
            'name' => 'New Admin',
            'email' => 'newadmin@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'admin',
        ]);

        $this->assertTrue(User::where('email', 'newadmin@example.com')->exists());
        $user = User::where('email', 'newadmin@example.com')->first();
        expect($user->role)->toBe('admin');
    });

    test('new admin user has admin record created', function () {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('register.store'), [
            'name' => 'New Admin',
            'email' => 'newadmin@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'admin',
        ]);

        $user = User::where('email', 'newadmin@example.com')->first();
        expect($user->admin)->not->toBeNull();
    });

    test('guest cannot register as admin', function () {
        $response = $this->post(route('register.store'), [
            'name' => 'Hacker',
            'email' => 'hacker@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'admin',
        ]);

        $this->assertFalse(User::where('email', 'hacker@example.com')->exists());
    });
});

describe('Judge Registration', function () {
    test('authenticated admin can register a new judge user', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('register.store'), [
            'name' => 'New Judge',
            'email' => 'newjudge@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'judge',
        ]);

        $this->assertTrue(User::where('email', 'newjudge@example.com')->exists());
        $user = User::where('email', 'newjudge@example.com')->first();
        expect($user->role)->toBe('judge');
    });

    test('new judge user has judge record created', function () {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('register.store'), [
            'name' => 'New Judge',
            'email' => 'newjudge@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'judge',
        ]);

        $user = User::where('email', 'newjudge@example.com')->first();
        expect($user->judge)->not->toBeNull();
    });

    test('guest cannot register as judge', function () {
        $response = $this->post(route('register.store'), [
            'name' => 'Hacker',
            'email' => 'hacker@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'judge',
        ]);

        $this->assertFalse(User::where('email', 'hacker@example.com')->exists());
    });
});

describe('Committee Registration', function () {
    test('authenticated admin can register a new committee user', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('register.store'), [
            'name' => 'New Committee',
            'email' => 'newcomm@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'commitee',
            'department' => 'Engineering',
        ]);

        $this->assertTrue(User::where('email', 'newcomm@example.com')->exists());
        $user = User::where('email', 'newcomm@example.com')->first();
        expect($user->role)->toBe('commitee');
    });

    test('new committee user has committee record with department', function () {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('register.store'), [
            'name' => 'New Committee',
            'email' => 'newcomm@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'commitee',
            'department' => 'Engineering',
        ]);

        $user = User::where('email', 'newcomm@example.com')->first();
        expect($user->committee)->not->toBeNull();
        expect($user->committee->department)->toBe('Engineering');
    });

    test('department is required for committee registration', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('register.store'), [
            'name' => 'New Committee',
            'email' => 'newcomm@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'commitee',
        ]);

        $response->assertSessionHasErrors('department');
    });

    test('guest cannot register as committee', function () {
        $response = $this->post(route('register.store'), [
            'name' => 'Hacker',
            'email' => 'hacker@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'commitee',
            'department' => 'Hacking',
        ]);

        $this->assertFalse(User::where('email', 'hacker@example.com')->exists());
    });
});