<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCommittee;
use App\Models\Committee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        $events = Event::withCount('participations')
            ->latest()
            ->get();

        return inertia('admin/EventManagement/Index', [
            'events' => $events,
        ]);
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        return inertia('admin/EventManagement/Create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'status' => 'required|in:draft,registration_open,active,finished',
            'registration_start_time' => 'required|date',
            'registration_end_time' => 'required|date|after_or_equal:registration_start_time',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            
            'committees' => 'required|array|min:1',
            'committees.*.name' => 'required|string|max:255',
            'committees.*.email' => 'required|email|unique:users,email',
            'committees.*.department' => 'required|string|max:255',
            'committees.*.positon' => 'required|string|max:255',
        ], [
            'committees.*.email.unique' => 'Email ini sudah terdaftar di sistem.',
        ]);

        DB::transaction(function () use ($validated, $request) {
            
            $event = Event::create([
                'public_id' => Str::uuid()->toString(),
                'event_name' => $validated['event_name'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'status' => $validated['status'],
                'registration_start_time' => $validated['registration_start_time'],
                'registration_end_time' => $validated['registration_end_time'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'created_by' => $request->user()->user_id,
            ]);

            foreach ($validated['committees'] as $committeeData) {
                
                $user = User::create([
                    'public_id' => Str::uuid()->toString(),
                    'name' => $committeeData['name'],
                    'email' => $committeeData['email'],
                    'role' => 'committee', // Sesuaikan dengan enum di databasemu
                    'password' => Hash::make('password123'), // Default password
                ]);

                $user->sendEmailVerificationNotification();

                $committee = Committee::create([
                    'user_id' => $user->user_id,
                    'department' => $committeeData['department'],
                ]);

                EventCommittee::create([
                    'event_id' => $event->event_id,
                    'committee_id' => $committee->committee_id,
                    'positon' => $committeeData['positon'], 
                ]);
            }
        });

        return redirect()->route('admin.events.index')->with('status', 'Event dan akun panitia berhasil dibuat!');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        return inertia('admin/EventManagement/Show', [
            'event' => $event,
        ]);
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        return inertia('admin/EventManagement/Edit', [
            'event' => $event,
        ]);
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'event_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,registration_open,registration_closed,ongoing,finished'],
            'registration_start_time' => ['required', 'date'],
            'registration_end_time' => ['required', 'date', 'after:registration_start_time'],
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date', 'after:start_time'],
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('message', 'Event updated successfully.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('message', 'Event deleted successfully.');
    }
}
