<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

// Role-based route groups
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin routes - event management, judge/committee management
    // Route::get('/', fn () => inertia('admin/dashboard'))->name('dashboard');
    Route::get('/', fn () => response()->json(['message' => 'Admin Dashboard']))->name('dashboard');

    // TODO: Add admin controllers and routes
    // - Event CRUD
    // - Judge management
    // - Committee management
    // - Participation approvals
});

Route::middleware(['auth', 'verified', 'role:judge'])->prefix('judge')->name('judge.')->group(function () {
    // Judge routes - view assigned events, submit evaluations
    // Route::get('/', fn () => inertia('judge/dashboard'))->name('dashboard');
    Route::get('/', fn () => response()->json(['message' => 'Judge Dashboard']))->name('dashboard');

    // TODO: Add judge controllers and routes
    // - View assigned events
    // - Submit evaluation scores
    // - View event details
});

Route::middleware(['auth', 'verified', 'role:commitee'])->prefix('committee')->name('committee.')->group(function () {
    // Committee routes - view assigned events, audit
    // Route::get('/', fn () => inertia('committee/dashboard'))->name('dashboard');
    Route::get('/', fn () => response()->json(['message' => 'Judge Dashboard']))->name('dashboard');

    // TODO: Add committee controllers and routes
    // - View assigned events
    // - Audit participation data
    // - View event details
});

Route::middleware(['auth', 'verified', 'role:official_team'])->prefix('events')->name('events.')->group(function () {
    // Official Team routes - register for events, view participations
    // Route::get('/', fn () => inertia('events/dashboard'))->name('dashboard');
    Route::get('/', fn () => response()->json(['message' => 'Events Dashboard']))->name('dashboard');
    // TODO: Add official team controllers and routes
    // - Browse available events
    // - Register for events
    // - View registered events
    // - View participation status
    // - Upload payment proof
});

require __DIR__.'/settings.php';
