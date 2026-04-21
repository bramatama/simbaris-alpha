<?php

use App\Models\Admin;
use App\Models\Committee;
use App\Models\Judge;
use App\Models\OfficialTeam;
use App\Models\User;

describe('Role-Based Middleware', function () {
    describe('Unauthenticated Users', function () {
        test('unauthenticated users are redirected to login when accessing admin route', function () {
            $response = $this->get('/admin');
            $response->assertRedirect(route('login'));
        });

        test('unauthenticated users are redirected to login when accessing judge route', function () {
            $response = $this->get('/judge');
            $response->assertRedirect(route('login'));
        });

        test('unauthenticated users are redirected to login when accessing committee route', function () {
            $response = $this->get('/committee');
            $response->assertRedirect(route('login'));
        });

        test('unauthenticated users are redirected to login when accessing events route', function () {
            $response = $this->get('/events');
            $response->assertRedirect(route('login'));
        });
    });

    describe('Official Team Users', function () {
        beforeEach(function () {
            $user = User::factory()->create(['role' => 'official_team']);
            $user->officialTeam()->create([
                'institution' => 'SMA ABC',
                'level' => 'SMA',
                'province' => 'Jakarta',
                'city' => 'Jakarta Selatan',
            ]);
            $this->actingAs($user);
        });

        test('official_team users can access /events route', function () {
            $response = $this->get('/events');
            $response->assertStatus(200);
        });

        test('official_team users cannot access /admin route', function () {
            $response = $this->get('/admin');
            $response->assertRedirect(route('dashboard'));
            $response->assertSessionHas('error');
        });

        test('official_team users cannot access /judge route', function () {
            $response = $this->get('/judge');
            $response->assertRedirect(route('dashboard'));
            $response->assertSessionHas('error');
        });

        test('official_team users cannot access /committee route', function () {
            $response = $this->get('/committee');
            $response->assertRedirect(route('dashboard'));
            $response->assertSessionHas('error');
        });
    });

    describe('Admin Users', function () {
        beforeEach(function () {
            $user = User::factory()->create(['role' => 'admin']);
            $user->admin()->create();
            $this->actingAs($user);
        });

        test('admin users can access /admin route', function () {
            $response = $this->get('/admin');
            $response->assertStatus(200);
        });

        test('admin users cannot access /judge route', function () {
            $response = $this->get('/judge');
            $response->assertRedirect(route('dashboard'));
            $response->assertSessionHas('error');
        });

        test('admin users cannot access /committee route', function () {
            $response = $this->get('/committee');
            $response->assertRedirect(route('dashboard'));
            $response->assertSessionHas('error');
        });

        test('admin users cannot access /events route', function () {
            $response = $this->get('/events');
            $response->assertRedirect(route('dashboard'));
            $response->assertSessionHas('error');
        });
    });

    describe('Judge Users', function () {
        beforeEach(function () {
            $user = User::factory()->create(['role' => 'judge']);
            $user->judge()->create();
            $this->actingAs($user);
        });

        test('judge users can access /judge route', function () {
            $response = $this->get('/judge');
            $response->assertStatus(200);
        });

        test('judge users cannot access /admin route', function () {
            $response = $this->get('/admin');
            $response->assertRedirect(route('dashboard'));
            $response->assertSessionHas('error');
        });

        test('judge users cannot access /committee route', function () {
            $response = $this->get('/committee');
            $response->assertRedirect(route('dashboard'));
            $response->assertSessionHas('error');
        });

        test('judge users cannot access /events route', function () {
            $response = $this->get('/events');
            $response->assertRedirect(route('dashboard'));
            $response->assertSessionHas('error');
        });
    });

    describe('Committee Users', function () {
        beforeEach(function () {
            $user = User::factory()->create(['role' => 'committee']);
            $user->committee()->create(['department' => 'Auditor']);
            $this->actingAs($user);
        });

        test('committee users can access /committee route', function () {
            $response = $this->get('/committee');
            $response->assertStatus(200);
        });

        test('committee users cannot access /admin route', function () {
            $response = $this->get('/admin');
            $response->assertRedirect(route('dashboard'));
            $response->assertSessionHas('error');
        });

        test('committee users cannot access /judge route', function () {
            $response = $this->get('/judge');
            $response->assertRedirect(route('dashboard'));
            $response->assertSessionHas('error');
        });

        test('committee users cannot access /events route', function () {
            $response = $this->get('/events');
            $response->assertRedirect(route('dashboard'));
            $response->assertSessionHas('error');
        });
    });

    describe('Verified Users', function () {
        test('verified users reach the verification notice page', function () {
            $user = User::factory()->unverified()->create(['role' => 'official_team']);
            $user->officialTeam()->create([
                'institution' => 'SMA ABC',
                'level' => 'SMA',
                'province' => 'Jakarta',
                'city' => 'Jakarta Selatan',
            ]);
            $this->actingAs($user);
            
            // Unverified users trying to access dashboard should see verification notice
            $response = $this->get(route('verification.notice'));
            $response->assertStatus(200);
        });
    });
});
