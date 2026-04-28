<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\Committee;
use App\Models\EventCommittee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EventCommitteeController extends Controller
{
    public function index($public_id)
    {
        $event = Event::where('public_id', $public_id)
            ->with(['eventCommittees.committee.user'])
            ->firstOrFail();

        $role = auth()->user()?->role;
        $view = match ($role) {
            'admin' => 'admin/EventManagement/Committees/Index',
            'committee' => 'committee/HostedEvents/Committees/Index',
            default => abort(403, 'Unauthorized access'),
        };

        return inertia($view, ['event' => $event]);
    }

    public function store(Request $request, $public_id)
    {
        $event = Event::where('public_id', $public_id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255', 
        ]);

        DB::transaction(function () use ($validated, $event) {
            $user = User::create([
                'public_id' => Str::uuid()->toString(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => 'committee', 
                'password' => Hash::make('password123'),
            ]);

            // 2. Buat Profil Committee
            $committee = Committee::create([
                'user_id' => $user->user_id,
                'department' => $validated['department'],
            ]);

            // 3. Tugaskan ke Event Ini
            EventCommittee::create([
                'event_id' => $event->event_id,
                'committee_id' => $committee->committee_id, 
                'positon' => $validated['positon'], 
            ]);
        });

        return back()->with('status', 'Panitia baru berhasil ditambahkan ke acara ini!');
    }

    public function destroy($public_id, $event_committee_id)
    {
        EventCommittee::where('event_committee_id', $event_committee_id)->delete();

        return back()->with('status', 'Panitia berhasil diberhentikan dari acara ini.');
    }
}