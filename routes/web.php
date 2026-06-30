<?php

use App\Http\Controllers\EventCommitteeController;
use App\Http\Controllers\EventJudgeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\ParticipationController;
use App\Models\Participation;
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
            'committee' => inertia('committee/dashboard'),
            'official_team' => inertia('official_team/dashboard'),
            default => abort(403, 'Unauthorized access'),
        };
    })->name('dashboard');

    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/my-events', [EventController::class, 'my_events'])->name('events.my_events');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->middleware('throttle:6,1')->name('user-password.update');
});

// Role-based route groups
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // User management routes
    Route::resource('users', UserController::class)->only(['index', 'show', 'update', 'destroy']);

    Route::resource('events', EventController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    
    Route::get('events/{event:public_id}/information', [EventController::class, 'show'])->name('events.show');

    Route::delete('events/{event:public_id}/committees/{event_committee:event_committee_id}', [EventCommitteeController::class, 'destroy'])->name('events.committees.destroy');
    Route::resource('events/{event:public_id}/committees', EventCommitteeController::class)->only(['index', 'store']);

    Route::delete('events/{event:public_id}/judges/{event_judge:event_judge_id}', [EventJudgeController::class, 'destroy'])->name('events.judges.destroy');
    Route::resource('events/{event:public_id}/judges', EventJudgeController::class)->only(['index', 'store']);

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

Route::middleware(['auth', 'verified', 'role:committee'])->prefix('committee')->name('committee.')->group(function () {
    Route::resource('events', EventController::class)->only(['edit', 'update']);

    Route::get('events/{event:public_id}/information', [EventController::class, 'show'])->name('events.show');

    Route::delete('events/{event:public_id}/committees/{event_committee:event_committee_id}', [EventCommitteeController::class, 'destroy'])->name('events.committees.destroy');
    Route::resource('events/{event:public_id}/committees', EventCommitteeController::class)->only(['index', 'store']);

    Route::delete('events/{event:public_id}/judges/{event_judge:event_judge_id}', [EventJudgeController::class, 'destroy'])->name('events.judges.destroy');
    Route::resource('events/{event:public_id}/judges', EventJudgeController::class)->only(['index', 'store']);

    // Committee routes - view assigned events, audit
    // TODO: Add committee controllers and routes
    // - View assigned events
    // - Audit participation data
    // - View event details
});

Route::middleware(['auth', 'verified', 'role:official_team'])->prefix('official_team')->name('official_team.')->group(function () {
    Route::get('events/{event:public_id}/enroll', [ParticipationController::class, 'create'])->name('events.official_team.create');
    // Official Team routes - register for events, view participations
    // TODO: Add official team controllers and routes
    // - Browse available events
    // - Register for events
    // - View registered events
    // - View participation status
    // - Upload payment proof
});