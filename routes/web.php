<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;

Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function ()  {
        $role = Auth::user()->role;

        return match ($role){
            'admin' => inertia('admin/dashboard'),
            'judge' => inertia('judge/dashboard'),
            'commitee' => inertia('committee/dashboard'),
            'official_team' => inertia('official_team/dashboard'),
            default => abort(403, 'Unauthorized access'),
        };
    })->name('dashboard');
});

// Role-based route groups
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin routes - event management, judge/committee management

    // TODO: Add admin controllers and routes
    // - Event CRUD
    // - Judge management
    // - Committee management
    // - Participation approvals
});

Route::middleware(['auth', 'verified', 'role:judge'])->prefix('judge')->name('judge.')->group(function () {
    // Judge routes - view assigned events, submit evaluations

    // TODO: Add judge controllers and routes
    // - View assigned events
    // - Submit evaluation scores
    // - View event details
});

Route::middleware(['auth', 'verified', 'role:commitee'])->prefix('committee')->name('committee.')->group(function () {
    // Committee routes - view assigned events, audit
    // TODO: Add committee controllers and routes
    // - View assigned events
    // - Audit participation data
    // - View event details
});

Route::middleware(['auth', 'verified', 'role:official_team'])->prefix('official_team')->name('official_team.')->group(function () {
    // Official Team routes - register for events, view participations
    // TODO: Add official team controllers and routes
    // - Browse available events
    // - Register for events
    // - View registered events
    // - View participation status
    // - Upload payment proof
});

require __DIR__.'/settings.php';
